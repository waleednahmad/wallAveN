<?php

namespace App\Mail;

use App\Models\Dealer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewDealerApplicationReceived extends Mailable
{
    use Queueable, SerializesModels;

    public $dealer;
    /**
     * Create a new message instance.
     */
    public function __construct(Dealer $dealer)
    {
        $this->dealer = $dealer;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Dealer Application Received',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.new-dealer-application-received',
            with: [
                'email' => $this->dealer->email,
                'name' => $this->dealer->name,
                'phone' => $this->dealer->phone,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
