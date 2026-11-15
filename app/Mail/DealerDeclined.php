<?php

namespace App\Mail;

use App\Models\Dealer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** Sent when an admin declines a dealer application; includes the reason when one was given. */
class DealerDeclined extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Dealer $dealer) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Your dealer application with ' . (getWebsiteTitle() ?: config('app.name')));
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.dealer-declined',
            with: ['dealer' => $this->dealer],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
