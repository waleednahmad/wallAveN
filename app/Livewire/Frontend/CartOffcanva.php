<?php

namespace App\Livewire\Frontend;

use App\Models\CartTemp;
use Livewire\Attributes\On;
use Livewire\Component;

class CartOffcanva extends Component
{

    public function removeFromCart($id)
    {
        CartTemp::find($id)->delete();
        $this->dispatch('success', 'Item removed from cart');
    }

    public function checkout()
    {
        if (auth('dealer')->check()) {
            $cartTemps = CartTemp::where('dealer_id', auth('dealer')->user()->id)->get();
            $total = $cartTemps->sum('total');
        } elseif (auth('representative')->check()) {
            $cartTemps = CartTemp::where('representative_id', auth('representative')->user()->id)->get();
            $total = $cartTemps->sum('total');
        } else {
            $cartTemps = [];
            $total = 0;
        }
        $totalQuantity = $cartTemps->sum('quantity');

        if ($totalQuantity < getMinimumnItemsCount()) {
            $this->dispatch('error', 'You need to have at least ' . getMinimumnItemsCount() . ' items in your cart to proceed.');
            return;
        }

        if ($total < getMinimumPrice()) {
            $this->dispatch('error', 'Your cart total must be at least ' . getMinimumPrice() . ' $ to proceed.');
            return;
        }
        $this->dispatch('openConfirmCheckoutModal');
    }

    #[On('openCartOffcanva')]
    #[On('closeCartOffcanva')]
    public function render()
    {
        if (auth('dealer')->check()) {
            $cartTemps = CartTemp::where('dealer_id', auth('dealer')->user()->id)->get();
            $total = $cartTemps->sum('total');
        } elseif (auth('representative')->check()) {
            $cartTemps = CartTemp::where('representative_id', auth('representative')->user()->id)->get();
            $total = $cartTemps->sum('total');
        } else {
            $cartTemps = [];
            $total = 0;
        }
        return view('livewire.frontend.cart-offcanva')->with([
            'cartTemps' => $cartTemps,
            'total' => $total,
        ]);
    }
}
