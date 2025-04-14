<?php

namespace App\Mail;

use App\Models\Representative;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RepresentativeAccepted extends Mailable
{
    use Queueable, SerializesModels;
    public $rep;
    /**
     * Create a new message instance.
     */
    public function __construct(Representative $rep)
    {
        $this->rep = $rep;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Representative Accepted',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.rep-accepted',
            with: [
                'rep' => $this->rep,
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
