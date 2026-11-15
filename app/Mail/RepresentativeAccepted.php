<?php

namespace App\Mail;

use App\Models\Representative;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** To the sales rep when the application is approved (layout: emails/layout.blade.php) */
class RepresentativeAccepted extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Representative $rep) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Your sales rep account is approved · ' . (getWebsiteTitle() ?: config('app.name')));
    }

    public function content(): Content
    {
        return new Content(view: 'emails.rep-accepted');
    }
}
