<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Accusé de réception envoyé au visiteur : sa copie du message.
 */
class ContactAcknowledgementMail extends Mailable
{
    use SerializesModels;

    public function __construct(public ContactMessage $contact)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nous avons bien reçu votre message — AluStock',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-ack',
            with: ['contact' => $this->contact],
        );
    }
}