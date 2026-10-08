<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Mail\ContactAcknowledgementMail;
use App\Mail\ContactMessageMail;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('public.contact');
    }

    public function send(ContactRequest $request): RedirectResponse
    {
        // 1. Trace d'abord : le message est en base même si l'envoi échoue.
        $contact = ContactMessage::create([
            ...$request->safe()->only(['nom', 'email', 'entreprise', 'sujet', 'message']),
            'ip'         => $request->ip(),
            'user_agent' => mb_substr((string) $request->userAgent(), 0, 255),
            'statut'     => ContactMessage::STATUT_EN_ATTENTE,
        ]);

        // 2. Envoi à l'adresse préconfigurée (Reply-To = visiteur).
        try {
            Mail::to(config('contact.to.address'), config('contact.to.name'))
                ->send(new ContactMessageMail($contact));

            $contact->marquerEnvoye();
        } catch (\Throwable $e) {
            Log::error('Contact : échec d\'envoi', [
                'contact_id' => $contact->id,
                'error'      => $e->getMessage(),
            ]);
            $contact->marquerEchec($e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Votre message n\'a pas pu être envoyé pour le moment. Il a été enregistré, réessayez dans quelques instants.');
        }

        // 3. Accusé de réception au visiteur (non bloquant).
        if (config('contact.send_acknowledgement')) {
            try {
                Mail::to($contact->email, $contact->nom)
                    ->send(new ContactAcknowledgementMail($contact));
            } catch (\Throwable $e) {
                Log::warning('Contact : échec de l\'accusé de réception', [
                    'contact_id' => $contact->id,
                    'error'      => $e->getMessage(),
                ]);
            }
        }

        return redirect()->to(route('vitrine.index') . '#contact')
        ->with('success', 'Message envoyé. Un accusé de réception vous a été envoyé par courriel.');
    }
}