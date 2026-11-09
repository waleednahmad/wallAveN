<?php

namespace App\Livewire\Frontend\Dealer\Dashboard;

use App\Models\Order;
use Livewire\Attributes\On;
use Livewire\Component;

class PreviewOrderModal extends Component
{

    public Order $order;

    #[On('setOrder')]
    public function setOrder(Order $order)
    {
        // Only the dealer who placed the order may preview it
        abort_unless((int) $order->dealer_id === (int) auth('dealer')->id(), 404);

        $this->order = $order->load('orderItems');
    }

    public function render()
    {
        return view('livewire.frontend.dealer.dashboard.preview-order-modal');
    }
}
