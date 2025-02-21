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
        } else {
            $this->total = 0;
        }
    }

    public function checkout()
    {
        // check if the user is logged in, then get the temp cart items and store them in the order and orfderItems and delete them

        if (auth('dealer')->check() || auth('representative')->check()) {
            DB::beginTransaction();
            try {
                $user = auth('dealer')->check() ? auth('dealer')->user() : auth('representative')->user();

                if (auth('dealer')->check()) {
                    $cartTemps = CartTemp::where('dealer_id', $user->id)->get();
                } else {
                    $cartTemps = CartTemp::where('representative_id', $user->id)->get();
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
                } else {
                    $order = $user->orders()->create($orderData);
                }

                foreach ($cartTemps as $cartTemp) {
                    $order->items()->create([
                        'product_id' => $cartTemp->product_id,
                        'variant_sku' => $cartTemp->variant_sku,
                        'variant_image' => $cartTemp->variant_image,
                        'title' => $cartTemp->title,
                        'vendor' => $cartTemp->vendor,
                        'option1_name' => $cartTemp->option1_name,
                        'option1_value' => $cartTemp->option1_value,
                        'option2_name' => $cartTemp->option2_name,
                        'option2_value' => $cartTemp->option2_value,
                        'option3_name' => $cartTemp->option3_name,
                        'option3_value' => $cartTemp->option3_value,
                        'sku' => $cartTemp->sku,
                        'quantity' => $cartTemp->quantity,
                        'price' => $cartTemp->price,
                        'total' => $cartTemp->total,
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
