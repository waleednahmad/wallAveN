<?php

namespace App\Livewire\Frontend\Modals;

use App\Models\Dealer;
use Livewire\Attributes\On;
use Livewire\Component;

class DealerSelectionModal extends Component
{
    public $search = '';

    public function selectDealer($dealerId)
    {
        $dealer = Dealer::find($dealerId);
        if (!$dealer) {
            $this->dispatch('error', 'Dealer not found');
            $this->dispatch('closeDealerSelectionModal');
            return;
        }


        if (auth('representative')->check()) {
            auth('representative')->user()->update([
                'buying_for_id' => $dealer->id
            ]);
        } elseif (auth('web')->check()) {
            auth('web')->user()->update([
                'buying_for_id' => $dealer->id
            ]);
        }

        // get the previous url from the web browser
        $url = url()->previous();
        $this->redirect($url);
    }

    public function removeDealer()
    {
        if (auth('representative')->check()) {
            auth('representative')->user()->update([
                'buying_for_id' => null
            ]);
        } elseif (auth('web')->check()) {
            auth('web')->user()->update([
                'buying_for_id' => null
            ]);
        }

        // get the previous url from the web browser
        $url = url()->previous();
        $this->redirect($url);
    }

    public function render()
    {
        if (auth('representative')->check()) {
            $dealers = Dealer::where('is_approved', true)
                ->where('status', true)
                ->where('referal_id', auth('representative')->user()->id)
                ->when($this->search, function ($query) {
                    return $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('phone', 'like', '%' . $this->search . '%')
                        ->orWhere('address', 'like', '%' . $this->search . '%')
                        ->orWhere('city', 'like', '%' . $this->search . '%')
                        ->orWhere('state', 'like', '%' . $this->search . '%')
                        ->orWhere('company_name', 'like', '%' . $this->search . '%');
                });
        } elseif (auth('web')->check()) {
            $dealers = Dealer::where('is_approved', true)
                ->where('status', true)
                ->when($this->search, function ($query) {
                    return $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('phone', 'like', '%' . $this->search . '%')
                        ->orWhere('address', 'like', '%' . $this->search . '%')
                        ->orWhere('city', 'like', '%' . $this->search . '%')
                        ->orWhere('state', 'like', '%' . $this->search . '%')
                        ->orWhere('company_name', 'like', '%' . $this->search . '%');
                });
        }

        return view('livewire.frontend.modals.dealer-selection-modal')->with([
            'dealers' => $dealers->get()
        ]);
    }
}
