<?php

namespace App\Livewire\Dashboard\AboundedCheckout;

use App\Models\CartTemp;
use App\Models\Dealer;
use Livewire\Attributes\On;
use Livewire\Component;

class PreviewAboundedCheckouts extends Component
{
    public $dealer, $orderItems;

    #[On('setDealer')]
    public function setDealer(Dealer $dealer, $addedBy = null)
    {
        $this->dealer = $dealer;
        $this->orderItems = CartTemp::where('dealer_id', $dealer->id)
            ->whereNull('representative_id')
            ->whereNull('admin_id')
            ->with(['product', 'variant'])
            ->get();
    }
    public function render()
    {
        return view('livewire.dashboard.abounded-checkout.preview-abounded-checkouts');
    }
}
