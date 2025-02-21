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
        $this->order = $order->load('items');
    }

    public function render()
    {
        return view('livewire.frontend.dealer.dashboard.preview-order-modal');
    }
}
