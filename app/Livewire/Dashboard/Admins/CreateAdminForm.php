<?php

namespace App\Livewire\Dashboard\Admins;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreateAdminForm extends Component
{

    #[Validate('required|string')]
    public $name;
    #[Validate('required|email|unique:users,email')]
    public $email;
    #[Validate('required|string|min:8')]
    public $password;
    #[Validate('required|same:password')]
    public $password_confirmation;

    public function save()
    {
        $this->validate();

        // Save data
        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);
        
        $this->reset();
        $this->dispatch('closeCreateOffcanvas');
        $this->dispatch('reloadAdmins');
        $this->dispatch('success', 'Admin created successfully');
    }

    public function render()
    {
        return view('livewire.dashboard.admins.create-admin-form');
    }
}
