<?php

namespace App\Livewire\Form;

use Livewire\Component;

class SelectDropdown extends Component
{
    // Data
    public array $items = [];
    public string $targetModel;

    // Configuration
    public bool $searchable = false;
    public bool $multiple = false;
    public bool $required = false;
    public string $placeholder = 'Select an option';
    public string $labelKey = 'name';
    public string $valueKey = 'id';
    public string $label = '';

    // State
    public $selected = null;
    public array $selectedMultiple = [];

    public function mount(
        array $items,
        string $targetModel,
        bool $searchable = false,
        bool $multiple = false,
        bool $required = false,
        string $placeholder = 'Select an option',
        string $labelKey = 'name',
        string $valueKey = 'id',
        string $label = '',
        $value = null
    ) {
        $this->items = $items;
        $this->targetModel = $targetModel;
        $this->searchable = $searchable;
        $this->multiple = $multiple;
        $this->required = $required;
        $this->placeholder = $placeholder;
        $this->labelKey = $labelKey;
        $this->valueKey = $valueKey;
        $this->label = $label;

        if ($value !== null) {
            if ($multiple) {
                $this->selectedMultiple = is_array($value) ? $value : [$value];
            } else {
                $this->selected = $value;
            }
        }
    }

    public function render()
    {
        return view('livewire.form.select-dropdown');
    }
}
