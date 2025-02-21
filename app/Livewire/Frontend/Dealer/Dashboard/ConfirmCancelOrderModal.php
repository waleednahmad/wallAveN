<?php

namespace App\Livewire\Frontend\Dealer\Dashboard;

use App\Models\Order;
use Livewire\Attributes\On;
use Livewire\Component;

class ConfirmCancelOrderModal extends Component
{
    public $order;
    #[On('setOrderId')]
    public function setOrderId($order)
    {
        $order = Order::find($order);
        if (!$order) {
            $this->dispatch('error', 'Order not found');
            $this->dispatch('closeConfirmModal');
            return;
        }
        $this->order = $order;
    }

    public function cancelOrder()
    {
        $this->order->update(['status' => 'canceled']);
        $this->dispatch('success', 'Order canceled successfully');
        $this->dispatch('closeConfirmModal');
        $this->dispatch('refreshOrders');
    }

    public function render()
    {
        return view('livewire.frontend.dealer.dashboard.confirm-cancel-order-modal');
    }
}
