<?php

namespace App\Livewire\Dashboard\Orders;

use App\Mail\OrderStatusUpdated;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * Order details in a side panel on the admin orders page: status steps with the next action,
 * dealer and shipping details, items and totals, print / PDF. Replaces the separate
 * "preview" and "change status" modals for the orders table.
 */
class OrderDrawer extends Component
{
    public ?int $orderId = null;

    /** Email the dealer when the status changes (the old status modal always emailed). */
    public bool $notifyDealer = true;

    /** Statuses an admin may set (same list as ChangeStatusModal; 'canceled' is the dealer's action). */
    private const STATUSES = ['pending', 'processing', 'completed', 'declined'];

    #[On('openOrderDrawer')]
    public function open($orderId): void
    {
        $this->orderId = (int) (is_array($orderId) ? ($orderId['orderId'] ?? 0) : $orderId);
        $this->notifyDealer = true;
        unset($this->order);

        if (!$this->order) {
            $this->dispatch('error', 'Order not found');
            return;
        }
        $this->dispatch('showOrderDrawer');
    }

    #[Computed]
    public function order(): ?Order
    {
        if (!$this->orderId) {
            return null;
        }

        return Order::with(['orderItems', 'dealer'])->find($this->orderId);
    }

    public function setStatus(string $status): void
    {
        $order = $this->order;
        if (!$order) {
            $this->dispatch('error', 'Order not found');
            return;
        }
        if (!in_array($status, self::STATUSES, true)) {
            $this->dispatch('error', 'Unknown status');
            return;
        }
        if ($order->status === 'canceled') {
            $this->dispatch('error', 'The dealer canceled this order');
            return;
        }
        if ($order->status === $status) {
            return;
        }

        $order->update(['status' => $status]);

        $emailed = false;
        if ($this->notifyDealer && $order->dealer?->email) {
            try {
                Mail::to($order->dealer->email)->send(new OrderStatusUpdated($order));
                $emailed = true;
            } catch (\Throwable $e) {
                report($e);
            }
        }

        unset($this->order);
        $this->dispatch('success', "Order #{$order->id} marked {$status}" . ($emailed ? ' · dealer emailed' : ''));
        $this->dispatch('refreshOrders');
    }

    public function render()
    {
        return view('livewire.dashboard.orders.order-drawer');
    }
}
