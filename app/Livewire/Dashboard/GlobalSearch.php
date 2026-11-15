<?php

namespace App\Livewire\Dashboard;

use App\Models\Dealer;
use App\Models\Order;
use App\Models\Product;
use Livewire\Component;

/**
 * Search box in the admin top bar: orders, dealers and products at once.
 * Orders open in the order side panel (?order=ID), dealers in the dealer details panel (?dealer=ID).
 */
class GlobalSearch extends Component
{
    public string $q = '';

    public function render()
    {
        $term = trim($this->q);
        $orders = $dealers = $products = collect();

        if (mb_strlen($term) >= 2) {
            $like = '%' . str_replace(['%', '_'], ['\%', '\_'], $term) . '%';
            $id = ctype_digit(ltrim($term, '#')) ? (int) ltrim($term, '#') : null;

            $orders = Order::with('dealer:id,company_name,name')
                ->where(function ($q) use ($like, $id) {
                    if ($id) {
                        $q->where('id', $id);
                    }
                    $q->orWhere('po_number', 'like', $like)
                        ->orWhereHas('dealer', fn ($d) => $d->where('company_name', 'like', $like)->orWhere('email', 'like', $like));
                })
                ->latest()->take(5)->get(['id', 'dealer_id', 'status', 'total', 'created_at']);

            $dealers = Dealer::where(function ($q) use ($like) {
                $q->where('company_name', 'like', $like)
                    ->orWhere('name', 'like', $like)
                    ->orWhere('email', 'like', $like)
                    ->orWhere('phone', 'like', $like);
            })->orderBy('company_name')->take(5)->get(['id', 'company_name', 'name', 'email', 'is_approved', 'rejected_at']);

            $products = Product::where(function ($q) use ($like) {
                $q->where('name', 'like', $like)->orWhere('sku', 'like', $like);
            })->orderBy('name')->take(5)->get(['id', 'name', 'sku', 'image', 'status']);
        }

        return view('livewire.dashboard.global-search', compact('term', 'orders', 'dealers', 'products'));
    }
}
