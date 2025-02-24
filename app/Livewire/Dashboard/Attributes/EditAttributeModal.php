<?php

namespace App\Livewire\Dashboard\Attributes;

use App\Models\Attribute;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class EditAttributeModal extends Component
{
    // ===========================
    // Attributes
    // ===========================
    public $attribute;
    #[Validate('required|string|max:255')]
    public $name = '';



    #[Validate([
        'values' => 'required',
        'values.*' => [
            'required',
            'min:1',
        ],
    ])]
    public  $values = [];

    #[On('setEditAttribute')]
    public function setAttribute(Attribute $attribute)
    {
        $this->attribute = $attribute;
        $this->name = $attribute->name;
        $this->values = $attribute->values->map(function ($value) {
            return [
                'value' => $value->value,
            ];
        })->toArray();
    }

    // ===========================
    // Methods
    // ===========================
    public function save()
    {
        $this->validate();
        DB::beginTransaction();
        try {
            // Create the attribute
            $this->attribute->update([
                'name' => $this->name,
            ]);

            // Delete existing attribute values
            $this->attribute->values()->delete();

            // Create new attribute values if any
            if (count($this->values)) {
                $this->attribute->values()->createMany($this->values);
            }


            DB::commit();
            $this->dispatch('success', 'Attribute updated successfully');
            $this->dispatch('refrsehAttributesList');
            $this->dispatch('closeEditModal');
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
        return view('livewire.dashboard.attributes.edit-attribute-modal');
    }
}
