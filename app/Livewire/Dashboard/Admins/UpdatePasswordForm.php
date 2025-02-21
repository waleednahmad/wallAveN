<?php

namespace App\Livewire\Dashboard\Admins;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\On;
use Livewire\Component;

class UpdatePasswordForm extends Component
{
    public $admin = null;
    public $password;
    public $password_confirmation;

    // --------------------------------
    // Mounting
    // --------------------------------
    #[On('mountPass')]
    public function mount($admin)
    {
        if ($admin) {
            $this->admin = User::find($admin['id']);
        }
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
        $this->admin->update([
            'password' => Hash::make($this->password),
        ]);

        $this->reset();
        $this->dispatch('closeUpdatePasswordOffcanvas');
        $this->dispatch('reloadAdmins');
        $this->dispatch('success', 'Password updated successfully');
    }


    public function render()
    {
        return view('livewire.dashboard.admins.update-password-form');
    }
}
