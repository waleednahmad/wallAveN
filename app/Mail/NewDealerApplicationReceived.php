<?php

namespace App\Mail;

use App\Models\Dealer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** To the admins: a dealer application is waiting (layout: emails/layout.blade.php) */
class NewDealerApplicationReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Dealer $dealer) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'New dealer application: ' . ($this->dealer->company_name ?: $this->dealer->name));
    }

    public function content(): Content
    {
        return new Content(view: 'emails.new-dealer-application-received');
    }
}
