<?php

namespace App\Livewire\Dashboard\Modals\AttributeValues;

use App\Models\Attribute;
use Livewire\Attributes\On;
use Livewire\Component;

class AddNewAttributeValueModal extends Component
{
    public $attribute;
    public $value = '';


    #[On('setAttributeValue')]
    public function setAttribute(Attribute $attribute)
    {
        $this->attribute = $attribute;
    }


    public function save()
    {
        $this->validate([
            'value' => 'required|string|max:255',
        ]);

        // Check if the value already exists
        if ($this->attribute->values()->where('value', $this->value)->exists()) {
            $this->dispatch('error', 'Attribute value already exists');
            return;
        }
        // Create the attribute value
        $this->attribute->values()->create([
            'value' => $this->value,
        ]);

        $this->dispatch('success', 'Attribute value created successfully');
        $this->dispatch('refreshAttributeValuesList');
        $this->dispatch('closeAddNewAttributeValueModal');
        $this->reset();
    }

    public function render()
    {
        return view('livewire.dashboard.modals.attribute-values.add-new-attribute-value-modal');
    }
}
