<?php

namespace App\Mail;

use App\Models\Dealer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** To the applicant right after the dealer sign-up (layout: emails/layout.blade.php) */
class DealerApplicationReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ?Dealer $dealer = null) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'We received your dealer application · ' . (getWebsiteTitle() ?: config('app.name')));
    }

    public function content(): Content
    {
        return new Content(view: 'emails.dealer-application-received');
    }
}
