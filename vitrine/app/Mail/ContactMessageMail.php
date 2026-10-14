<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Courriel reçu par l'équipe (adresse préconfigurée dans config/contact.php).
 * Le Reply-To est le courriel du visiteur : "Répondre" écrit directement à lui.
 */
class ContactMessageMail extends Mailable
{
    use SerializesModels;

    public function __construct(public ContactMessage $contact)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [new Address($this->contact->email, $this->contact->nom)],
            subject: '[Contact ' . $this->brand() . '] ' . $this->contact->sujet,
        );
    }

    private function brand(): string
    {
        return config('vitrine.brand.first') . config('vitrine.brand.second');
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact',
            with: ['contact' => $this->contact],
        );
    }
}