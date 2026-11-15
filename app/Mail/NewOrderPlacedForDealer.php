<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** Order confirmation. $audience: 'dealer', 'rep' or 'admin' (sets the wording and where the button goes) (layout: emails/layout.blade.php) */
class NewOrderPlacedForDealer extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order, public string $audience = 'dealer') {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: ($this->audience === 'dealer' ? 'We received your order #' : 'New order #') . $this->order->id . ' · ' . ($this->order->dealer?->company_name ?: (getWebsiteTitle() ?: config('app.name'))));
    }

    public function content(): Content
    {
        return new Content(view: 'emails.new-order-placed-for-dealer');
    }
}
