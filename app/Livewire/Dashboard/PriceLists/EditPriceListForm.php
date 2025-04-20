<?php

namespace App\Livewire\Dashboard\PriceLists;

use App\Models\PriceList;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class EditPriceListForm extends Component
{

    public $priceList;
    #[Validate(['required', 'string', 'max:255'])]
    public $name;

    #[Validate(['numeric', 'between:1,100'])]
    public $percentage = 1;

    public $is_default = false;


    #[On('editPriceList')]
    public function edit(PriceList $priceList)
    {
        $this->priceList = $priceList;
        $this->name = $priceList->name;
        $this->percentage = $priceList?->percentage;
        $this->is_default = $priceList->is_default;
    }

    public function save()
    {
        $this->validate();

        $this->priceList->update([
            'name' => $this->name,
            'percentage' => $this->percentage,
        ]);

        // Remove all acrt temps from the related dealers
        $this->priceList->dealers()->each(function ($dealer) {
            $dealer->cartTemps()->delete();
        });

        $this->reset();
        $this->dispatch('success', 'Price List updated successfully.');
        $this->dispatch('refreshPriceLists');
        $this->dispatch('closeEditForm');
    }
    public function render()
    {
        return view('livewire.dashboard.price-lists.edit-price-list-form');
    }
}
