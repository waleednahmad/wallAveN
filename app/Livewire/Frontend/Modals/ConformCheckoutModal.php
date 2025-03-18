<?php

namespace App\Livewire\Frontend\Modals;

use App\Models\CartTemp;
use App\Models\Dealer;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class ConformCheckoutModal extends Component
{
    public $total, $po_number;

    #[On('openConfirmCheckoutModal')]
    public function getTotal()
    {
        if (auth('dealer')->check()) {
            $cartTemps = CartTemp::where('dealer_id', auth('dealer')->user()->id)->get();
            $this->total = $cartTemps->sum('total');
        } elseif (auth('representative')->check()) {
            $cartTemps = CartTemp::where('representative_id', auth('representative')->user()->id)->get();
            $this->total = $cartTemps->sum('total');
        } elseif (auth('web')->check()) { // admin check
            $cartTemps = CartTemp::where('admin_id', auth('web')->user()->id)->get();
            $this->total = $cartTemps->sum('total');
        } else {
            $this->total = 0;
        }
    }

    public function checkout()
    {
        // check if the user is logged in, then get the temp cart items and store them in the order and orfderItems and delete them

        if (auth('dealer')->check() || auth('representative')->check() || auth('web')->check()) {
            DB::beginTransaction();
            try {
                if (auth('dealer')->check()) {
                    $user = auth('dealer')->user();
                    $cartTemps = CartTemp::where('dealer_id', $user->id)->get();
                } else if (auth('representative')->check()) {
                    $user = auth('representative')->user();
                    $cartTemps = CartTemp::where('representative_id', $user->id)->get();
                } elseif (auth('web')->check()) {
                    $user = auth('web')->user();
                    $cartTemps = CartTemp::where('admin_id', $user->id)->get();
                }

                if ($cartTemps->isEmpty()) {
                    $this->dispatch('error', 'Your cart is empty');
                    return;
                }

                $orderData = [
                    'total' => $cartTemps->sum('total'),
                    'quantity' => $cartTemps->sum('quantity'),
                    'po_number' => $this->po_number ?? null,
                ];

                if (auth('representative')->check()) {
                    $buyingFor = $user->buyingFor;
                    if (!$buyingFor) {
                        $this->dispatch('error', 'You need to select a dealer to place an order');
                        $this->dispatch('openDealerSelectionModal');
                        return;
                    }
                    $dealer = Dealer::find($buyingFor->id);
                    $orderData['representative_id'] = $user->id;
                    $order = $dealer->orders()->create($orderData);
                } elseif (auth('web')->check()) { // admin check
                    $buyingFor = $user->buyingFor;
                    if (!$buyingFor) {
                        $this->dispatch('error', 'You need to select a dealer to place an order');
                        $this->dispatch('openDealerSelectionModal');
                        return;
                    }
                    $dealer = Dealer::find($buyingFor->id);
                    $orderData['admin_id'] = $user->id;
                    $order = $dealer->orders()->create($orderData);
                } else {
                    $order = $user->orders()->create($orderData);
                }

                foreach ($cartTemps as $cartTemp) {
                    $order->orderItems()->create([
                        'product_id' => $cartTemp->product_id,
                        'variant_id' => $cartTemp->variant_id,
                        'item_type' => $cartTemp->item_type,
                        'name' => $cartTemp->name,
                        'image' => $cartTemp->image,
                        'vendor' => $cartTemp->vendor,
                        'sku' => $cartTemp->sku,
                        'price' => $cartTemp->price,
                        'total' => $cartTemp->total,
                        'quantity' => $cartTemp->quantity,
                        'attributes' => $cartTemp->attributes,
                    ]);
                }

                $user->cartTemps()->delete();
                $this->dispatch('success', 'Order placed successfully');
                $this->dispatch('closeCartOffcanva');
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $this->dispatch('error', $e->getMessage());
            }
        } else {
            $this->dispatch('error', 'Please login to place an order');
        }

        $this->dispatch('closeConfirmCheckoutModal');
    }


    public function render()
    {
        return view('livewire.frontend.modals.conform-checkout-modal');
    }
}
