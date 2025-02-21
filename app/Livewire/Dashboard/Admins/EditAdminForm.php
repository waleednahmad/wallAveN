<?php

namespace App\Livewire\Dashboard\Admins;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class EditAdminForm extends Component
{
    public $admin = null;
    public $name;
    public $email;
    public $password;
    public $password_confirmation;

    // --------------------------------
    // Validation rules
    // --------------------------------
    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
            ],
            'email' => [
                'required',
                'email',
                'unique:users,email,' . $this->admin['id'],
            ],
        ];
    }

    // --------------------------------
    // Mounting
    // --------------------------------
    #[On('mount')]
    public function mount($admin)
    {
        $this->reset();
        if ($admin) {
            $this->admin = User::find($admin['id']);
            $this->name = $admin['name'];
            $this->email = $admin['email'];
        }
    }

    // --------------------------------
    // Actions
    // --------------------------------
    public function save()
    {
        $this->validate($this->rules());

        // Save data
        $this->admin->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);
        
        $this->reset();
        $this->dispatch('closeEditOffcanvas');
        $this->dispatch('reloadAdmins');
        $this->dispatch('success', 'Admin updated successfully');
    }

    // --------------------------------
    // Rdendering
    // --------------------------------
    public function render()
    {
        return view('livewire.dashboard.admins.edit-admin-form');
    }
}
