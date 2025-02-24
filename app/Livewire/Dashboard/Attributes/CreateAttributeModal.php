<?php

namespace App\Livewire\Dashboard\Attributes;

use App\Models\Attribute;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreateAttributeModal extends Component
{
    // ===========================
    // Attributes
    // ===========================
    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate([
        'values' => 'required',
        'values.*' => [
            'required',
            'min:1',
        ],
    ])]
    public  $values = [
        [
            'value' => '',
        ]
    ];

    // ===========================
    // Methods
    // ===========================
    public function save()
    {
        $this->validate();
        DB::beginTransaction();
        try {
            // Create the attribute
            $attribute = Attribute::create([
                'name' => $this->name,

            ]);

            // Create the attribute values
            $attribute->values()->createMany($this->values);

            DB::commit();

            $this->dispatch('success', 'Attribute created successfully');
            $this->dispatch('refrsehAttributesList');
            $this->dispatch('closeCreateModal');
            $this->reset();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('error', 'Failed to create attribute: ' . $e->getMessage());
        }
    }


    // ===========================
    // Helper Methods
    // ===========================
    public function addValue()
    {
        $this->values[] = [
            'value' => '',
        ];
    }

    public function removeValue($index)
    {
        unset($this->values[$index]);
        $this->values = array_values($this->values);
        $this->dispatch('success', 'Value removed successfully');
    }

    public function render()
    {
        return view('livewire.dashboard.attributes.create-attribute-modal');
    }
}
