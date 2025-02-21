<?php

namespace App\Livewire\Dashboard\Vendors;

use App\Models\Vendor;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreateVendorOffcanva extends Component
{
    #[Validate('required|string')]
    public $name;
    #[Validate('required|boolean')]
    public $status = 1;


    public function save()
    {
        $this->validate();

        // Save data
        Vendor::create([
            'name' => $this->name,
            'password' => Hash::make('password'),
            'status' => $this->status,
        ]);

        $this->reset();
        $this->dispatch('closeCreateOffcanvas');
        $this->dispatch('reloadVendors');
        $this->dispatch('success', 'Vendor created successfully');
    }
    public function render()
    {
        return view('livewire.dashboard.vendors.create-vendor-offcanva');
    }
}
