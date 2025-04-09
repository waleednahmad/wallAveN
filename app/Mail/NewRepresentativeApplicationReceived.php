<?php

namespace App\Mail;

use App\Models\Representative;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewRepresentativeApplicationReceived extends Mailable
{
    use Queueable, SerializesModels;
    public $representative;

    /**
     * Create a new message instance.
     */
    public function __construct(Representative $representative)
    {
        $this->representative = $representative;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Representative Application Received',

        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.new-representative-application-received',
            with: [
                'email' => $this->representative->email,
                'name' => $this->representative->name,
                'phone' => $this->representative->phone,
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
