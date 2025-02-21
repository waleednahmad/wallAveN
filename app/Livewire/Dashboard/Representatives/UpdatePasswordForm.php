<?php

namespace App\Livewire\Dashboard\Representatives;

use App\Models\Representative;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\On;
use Livewire\Component;

class UpdatePasswordForm extends Component
{
    public $representative = null;
    public $password;
    public $password_confirmation;

    // --------------------------------
    // Mounting
    // --------------------------------
    #[On('setRepresentative')]
    public function setRepresentative($representative)
    {
        $this->representative = Representative::find($representative['id']);
    }

    // --------------------------------
    // Actions
    // --------------------------------
    public function save()
    {
        $this->validate([
            'password' => 'required|string|min:8',
            'password_confirmation' => 'required_with:password|same:password',
        ]);

        // Save data
        $this->representative->update([
            'password' => Hash::make($this->password),
        ]);

        $this->reset();
        $this->dispatch('closeUpdatePasswordOffcanvas');
        $this->dispatch('reloadRepresentatives');
        $this->dispatch('success', 'Password updated successfully');
    }


    public function render()
    {
        return view('livewire.dashboard.representatives.update-password-form');
    }
}
