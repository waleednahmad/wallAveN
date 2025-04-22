<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewOrderPlacedForDealer extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    /**
     * Create a new message instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order->load('dealer', 'orderItems');
    }


    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Order Placed',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $address = $this?->order?->dealer->address ? explode(',', $this?->order?->dealer->address)[0] : '---';
        $city = $this?->order?->dealer->city ?? '---';
        $state = $this?->order?->dealer->state ?? '---';
        $zip_code = $this?->order?->dealer->zip_code ?? '---';
        return new Content(
            view: 'emails.new-order-placed-for-dealer',
            with: [
                'dealer' => $this?->order?->dealer,
                'items' => $this->order->orderItems,
                'order' => $this->order,
                'email' => $this?->order?->dealer->email,
                'name' => $this?->order?->dealer->company_name,
                'phone' => $this?->order?->dealer->phone,
                'address' => $address,
                'city' => $city,
                'state' => $state,
                'zip_code' => $zip_code,
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
