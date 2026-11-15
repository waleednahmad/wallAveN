<?php

namespace App\Mail;

use App\Models\Dealer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** To the applicant when the dealer application is declined (layout: emails/layout.blade.php) */
class DealerDeclined extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Dealer $dealer) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'About your dealer application · ' . (getWebsiteTitle() ?: config('app.name')));
    }

    public function content(): Content
    {
        return new Content(view: 'emails.dealer-declined');
    }
}
