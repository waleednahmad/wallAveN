<?php

namespace App\Mail;

use App\Models\Representative;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** To the applicant right after the sales rep sign-up (layout: emails/layout.blade.php) */
class RepresentativeApplicationReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ?Representative $representative = null) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'We received your sales rep application · ' . (getWebsiteTitle() ?: config('app.name')));
    }

    public function content(): Content
    {
        return new Content(view: 'emails.representative-application-received');
    }
}
