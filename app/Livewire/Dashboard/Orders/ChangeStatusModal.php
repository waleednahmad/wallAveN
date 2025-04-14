<?php

namespace App\Livewire\Dashboard\Orders;

use App\Mail\OrderStatusUpdated;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class ChangeStatusModal extends Component
{
    public $order;
    public $status;


    #[On('setOrder')]
    public function setOrder($order)
    {
        $order = Order::find($order);
        if (!$order) {
            $this->dispatch('error', 'Order not found');
            $this->dispatch('closeChangeStatusModal');
            return;
        }
        $this->order = $order;
        $this->status = $order->status;
    }

    #[Computed()]
    public function statuses()
    {
        return [
            'pending',
            'processing',
            'completed',
            'declined',
        ];
    }

    public function changeStatus()
    {
        $this->validate([
            'status' => 'required|in:pending,processing,completed,declined',
        ]);

        $this->order->update([
            'status' => $this->status,
        ]);


        if ($this->order->dealerr) {
            Mail::to($this->order->dealerr->email)->send(new OrderStatusUpdated($this->order));
        }
        $this->dispatch('success', 'Order status updated successfully');
        $this->dispatch('closeChangeStatusModal');
        $this->dispatch('refreshOrders');
    }

    public function render()
    {
        return view('livewire.dashboard.orders.change-status-modal');
    }
}
