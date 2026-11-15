<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** To the dealer when an admin changes the order status (layout: emails/layout.blade.php) */
class OrderStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Order #' . $this->order->id . ' is now ' . strtolower($this->order->status) . ' · ' . (getWebsiteTitle() ?: config('app.name')));
    }

    public function content(): Content
    {
        return new Content(view: 'emails.order-status-updated');
    }
}
