<?php

namespace App\Livewire\Dashboard\Dealers;

use App\Models\CartTemp;
use App\Models\Dealer;
use App\Models\Order;
use Livewire\Attributes\On;
use Livewire\Component;

/** Dealer profile in the "Dealer Details" panel: buying summary, open cart, recent orders and details. */
class ShowDealer extends Component
{
    public $dealer = null;

    #[On('setDealer')]
    public function setDealer($dealer)
    {
        $this->dealer = Dealer::with('referalTo:id,name', 'priceList:id,name')->find($dealer['id'] ?? null);
    }

    public function render()
    {
        $stats = $recentOrders = $cart = null;

        if ($this->dealer) {
            $sales = Order::where('dealer_id', $this->dealer->id)->whereIn('status', ['pending', 'processing', 'completed']);
            $stats = (clone $sales)->selectRaw('COUNT(*) as orders, COALESCE(SUM(total),0) as sales, MAX(created_at) as last_order')->first();
            $recentOrders = Order::where('dealer_id', $this->dealer->id)->latest()->take(5)->get(['id', 'status', 'total', 'quantity', 'created_at']);
            $cart = CartTemp::where('dealer_id', $this->dealer->id)->whereNull('representative_id')->whereNull('admin_id')
                ->selectRaw('COALESCE(SUM(total),0) as amount, COALESCE(SUM(quantity),0) as items, MAX(updated_at) as last_activity')
                ->first();
        }

        return view('livewire.dashboard.dealers.show-dealer', compact('stats', 'recentOrders', 'cart'));
    }
}
