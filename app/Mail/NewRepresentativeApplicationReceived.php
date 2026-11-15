<?php

namespace App\Mail;

use App\Models\Representative;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** To the admins: a sales rep application is waiting (layout: emails/layout.blade.php) */
class NewRepresentativeApplicationReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Representative $representative) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'New sales rep application: ' . $this->representative->name);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.new-representative-application-received');
    }
}
