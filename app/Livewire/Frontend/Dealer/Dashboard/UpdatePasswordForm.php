<?php

namespace App\Livewire\Frontend\Dealer\Dashboard;

use Livewire\Attributes\Validate;
use Livewire\Component;

class UpdatePasswordForm extends Component
{
    #[Validate('required|min:8|confirmed')]
    public $password;
    public $password_confirmation;

    public function updatePassword()
    {
        $this->validate();

        auth('dealer')->user()->update([
            'password' => bcrypt($this->password),
        ]);

        $this->reset();
        $this->dispatch('success', 'Password updated successfully.');
    }

    public function render()
    {
        return view('livewire.frontend.dealer.dashboard.update-password-form');
    }
}
