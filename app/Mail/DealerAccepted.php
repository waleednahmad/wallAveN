<?php

namespace App\Mail;

use App\Models\Dealer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DealerAccepted extends Mailable
{
    use Queueable, SerializesModels;

    public Dealer $dealer;
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
            subject: 'Dealer Accepted',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.dealer-accepted',
            with: [
                'dealer' => $this->dealer,
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
