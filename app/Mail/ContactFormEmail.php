<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** To the admins: a message from the website contact form. Reply goes straight to the sender. */
class ContactFormEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $name,
        public string $email,
        public string $phone,
        public string $emailSubject,
        public string $emailMessage
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: filter_var($this->email, FILTER_VALIDATE_EMAIL) ? [new Address($this->email, $this->name)] : [],
            subject: 'Website message: ' . ($this->emailSubject ?: 'no subject') . ' · ' . $this->name,
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.contact-form');
    }
}
