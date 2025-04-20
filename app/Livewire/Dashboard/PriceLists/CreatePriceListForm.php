<?php

namespace App\Livewire\Dashboard\PriceLists;

use App\Models\PriceList;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreatePriceListForm extends Component
{

    #[Validate(['required', 'string', 'max:255'])]
    public $name;

    #[Validate(['numeric', 'between:1,100'])]
    public $percentage = 1;



    public function save()
    {

        $this->validate();

        PriceList::create([
            'name' => $this->name,
            'percentage' => $this->percentage,

        ]);

        $this->reset();
        $this->dispatch('success', 'Price list created successfully.');
        $this->dispatch('refreshPriceLists');
        $this->dispatch('closeCreateForm');
    }
    public function render()
    {
        return view('livewire.dashboard.price-lists.create-price-list-form');
    }
}
