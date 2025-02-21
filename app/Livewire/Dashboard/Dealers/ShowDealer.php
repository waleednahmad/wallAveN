<?php

namespace App\Livewire\Dashboard\Dealers;

use App\Models\Dealer;
use Livewire\Attributes\On;
use Livewire\Component;

class ShowDealer extends Component
{

    public $dealer = null;


    // --------------------------------
    // Mounting
    // --------------------------------
    #[On('setDealer')]
    public function setDealer($dealer)
    {
        $this->dealer = Dealer::find($dealer['id']);
    }

    public function render()
    {
        return view('livewire.dashboard.dealers.show-dealer');
    }
}
