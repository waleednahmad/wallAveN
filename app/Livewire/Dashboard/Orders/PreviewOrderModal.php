<?php

namespace App\Livewire\Dashboard\Orders;

use App\Models\Order;
use Livewire\Attributes\On;
use Livewire\Component;

class PreviewOrderModal extends Component
{

    public Order $order;

    #[On('setOrder')]
    public function setOrder(Order $order)
    {
        $this->order = $order->load('items');
    }

    public function render()
    {
        return view('livewire.dashboard.orders.preview-order-modal');
    }
}
