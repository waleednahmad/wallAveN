<?php

namespace App\Livewire\Dashboard\Dealers;

use App\Models\Dealer;
use App\Models\PriceList;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class SetPriceListModal extends Component
{
    public $price_list_id;
    public $dealers = [];

    #[On('setDealers')]
    public function setdealers($dealers = [])
    {
        $this->dealers = is_array($dealers) ? array_unique($dealers) : [];
    }

    #[Computed()]
    public function priceLists()
    {
        return PriceList::all();
    }


    public function save()
    {
        $this->validate([
            'price_list_id' => 'nullable',
        ]);

        if (empty($this->dealers)) {
            $this->dispatch('error', ['message' => 'No dealers selected']);
            return;
        }

        foreach ($this->dealers as $dealerId) {
            $dealer = Dealer::find($dealerId);
            if ($dealer) {
                if ($this->price_list_id > 0) {
                    $dealer->price_list_id = $this->price_list_id;
                    $dealer->save();
                } else {
                    $dealer->price_list_id = null;
                    $dealer->save();
                }
            }
        }

        // Clear the selected dealers after saving
        $this->dealers = [];

        $this->dispatch('closePriceListMoodal');
        $this->reset();
        return redirect()->route('dashboard.dealers.index')->with('success', 'Price list updated successfully');
    }

    public function render()
    {
        return view('livewire.dashboard.dealers.set-price-list-modal');
    }
}
