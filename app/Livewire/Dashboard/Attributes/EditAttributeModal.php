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
                'id' => $value->id,
            ];
        })->toArray();
    }

    // ===========================
    // Methods
    // ===========================
    public function save()
    {
        $this->validate();
        if (!$this->checkOnValuesDuplicates()) {
            return;
        }
        DB::beginTransaction();
        try {
            // Update the attribute
            $this->attribute->update([
                'name' => $this->name,
            ]);

            // Get existing values
            $existingValues = $this->attribute->values->keyBy('id')->toArray();

            // Update or create new values
            foreach ($this->values as $value) {
                if (isset($value['id']) && isset($existingValues[$value['id']])) {
                    // Check if the value has changed
                    if ($existingValues[$value['id']]['value'] !== $value['value']) {
                        // Update existing value
                        $this->attribute->values()->where('id', $value['id'])->update([
                            'value' => $value['value'],
                        ]);
                    }
                    unset($existingValues[$value['id']]);
                } else {
                    // Create new value
                    $this->attribute->values()->create([
                        'value' => $value['value'],
                    ]);
                }
            }

            // Delete values that are no longer present
            foreach ($existingValues as $value) {
                $this->attribute->values()->where('id', $value['id'])->delete();
            }

            DB::commit();
            $this->dispatch('success', 'Attribute updated successfully');
            $this->dispatch('refreshAttributesList');
            $this->dispatch('closeEditModal');
            $this->reset();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('error', 'Failed to update attribute: ' . $e->getMessage());
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

    public function checkOnValuesDuplicates()
    {
        $values = array_column($this->values, 'value');
        $duplicates = array_unique(array_diff_assoc($values, array_unique($values)));
        if (count($duplicates) > 0) {
            $this->dispatch('error', 'Duplicate values found: ' . implode(', ', $duplicates));
            return false;
        }
        return true;
    }



    public function render()
    {
        return view('livewire.dashboard.attributes.edit-attribute-modal');
    }
}
