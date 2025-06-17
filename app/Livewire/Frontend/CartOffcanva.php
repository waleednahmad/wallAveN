<?php

namespace App\Livewire\Frontend;

use App\Models\CartTemp;
use Livewire\Attributes\On;
use Livewire\Component;

class CartOffcanva extends Component
{
    public $showQuantityControls = []; // Track which items show quantity controls

    public function removeFromCart($id)
    {
        CartTemp::find($id)->delete();

        // Remove from quantity controls array if it exists
        $id = (string) $id;
        if (in_array($id, $this->showQuantityControls)) {
            $this->showQuantityControls = array_values(array_filter($this->showQuantityControls, function ($itemId) use ($id) {
                return (string) $itemId !== $id;
            }));
        }

        $this->dispatch('success', 'Item removed from cart');
    }

    public function toggleQuantityControls($id)
    {
        // Convert to string to ensure consistent comparison
        $id = (string) $id;

        if (in_array($id, $this->showQuantityControls)) {
            // Remove the item from the array and re-index
            $this->showQuantityControls = array_values(array_filter($this->showQuantityControls, function ($itemId) use ($id) {
                return (string) $itemId !== $id;
            }));
        } else {
            // Add the item to the array
            $this->showQuantityControls[] = $id;
        }

        // Force component refresh to ensure UI updates
        $this->dispatch('$refresh');
    }

    public function increaseQuantity($id)
    {
        $cartItem = CartTemp::find($id);
        if ($cartItem) {
            $cartItem->quantity += 1;
            $cartItem->total = $cartItem->quantity * $cartItem->price;
            $cartItem->save();
            $this->dispatch('success', 'Quantity updated');
            $this->dispatch('refresh');
        }
    }

    public function decreaseQuantity($id)
    {
        $cartItem = CartTemp::find($id);
        if ($cartItem && $cartItem->quantity > 1) {
            $cartItem->quantity -= 1;
            $cartItem->total = $cartItem->quantity * $cartItem->price;
            $cartItem->save();
            $this->dispatch('success', 'Quantity updated');
            $this->dispatch('refresh');
        } else {
            $this->dispatch('error', 'Minimum quantity is 1');
        }
    }

    public function updateQuantity($id, $quantity)
    {
        $quantity = (int) $quantity; // Ensure it's an integer
        $cartItem = CartTemp::find($id);
        if ($cartItem && $quantity > 0) {
            $cartItem->quantity = $quantity;
            $cartItem->total = $cartItem->quantity * $cartItem->price;
            $cartItem->save();
            $this->dispatch('success', 'Quantity updated');
            // Force re-render to update the view
            $this->dispatch('refresh');
        } else {
            $this->dispatch('error', 'Invalid quantity');
        }
    }

    public function checkout()
    {
        if (auth('dealer')->check()) {
            $cartTemps = CartTemp::where('dealer_id', auth('dealer')->user()->id)->get();
            $total = $cartTemps->sum('total');
        } elseif (auth('representative')->check()) {
            $cartTemps = CartTemp::where('representative_id', auth('representative')->user()->id)->get();
            $total = $cartTemps->sum('total');
        } elseif (auth('web')->check()) { // admin check
            $cartTemps = CartTemp::where('admin_id', auth('web')->user()->id)->get();
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
    #[On('refresh')]
    public function render()
    {
        if (auth('dealer')->check()) {
            $cartTemps = CartTemp::where('dealer_id', auth('dealer')->user()->id)
                ->whereNull('representative_id')
                ->whereNull('admin_id')
                ->get();
            $total = $cartTemps->sum('total');
        } elseif (auth('representative')->check()) {
            $cartTemps = CartTemp::where('representative_id', auth('representative')->user()->id)
                ->when(auth('representative')->user()->buyingFor()->exists(), function ($query) {
                    $query->where('dealer_id', auth('representative')->user()->buyingFor->id);
                })
                ->whereNull('admin_id')
                ->get();
            $total = $cartTemps->sum('total');
        } elseif (auth('web')->check()) { // admin check
            $cartTemps = CartTemp::where('admin_id', auth('web')->user()->id)
                ->when(auth('web')->user()->buyingFor()->exists(), function ($query) {
                    $query->where('dealer_id', auth('web')->user()->buyingFor->id);
                })
                ->whereNull('representative_id')
                ->get();
            $total = $cartTemps->sum('total');
        } else {
            $cartTemps = [];
            $total = 0;
        }

        info('test');
        return view('livewire.frontend.cart-offcanva')->with([
            'cartTemps' => $cartTemps,
            'total' => $total,
        ]);
    }
}
