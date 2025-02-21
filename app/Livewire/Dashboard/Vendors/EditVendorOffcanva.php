<?php

namespace App\Livewire\Dashboard\Vendors;

use App\Models\Vendor;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class EditVendorOffcanva extends Component
{
    public $vendor;
    public $name;
    public $email;
    public $phone;
    public $status = 1;
    public $password;
    public $password_confirmation;

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'min:3'],
            'status' => ['required', 'boolean'],
        ];
    }

    #[On('editVendor')]
    public function edit(Vendor $vendor)
    {
        $this->vendor = $vendor;
        $this->name = $vendor['name'];
        $this->email = $vendor['email'];
        $this->phone = $vendor['phone'];
        $this->status = $vendor['status'];
        $this->password = '';
        $this->password_confirmation = '';
    }

    public function save()
    {
        $this->validate();

        // Save data
        $this->vendor->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'status' => $this->status,
        ]);

        if ($this->password) {
            $this->vendor->update([
                'password' => Hash::make($this->password),
            ]);
        }

        $this->reset();
        $this->dispatch('closeEditOffcanvas');
        $this->dispatch('reloadVendors');
        $this->dispatch('success', 'Vendor updated successfully');
    }

    public function render()
    {
        return view('livewire.dashboard.vendors.edit-vendor-offcanva');
    }
}
