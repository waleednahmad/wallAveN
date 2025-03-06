<?php

namespace App\Livewire\Dashboard\Products;

use App\Models\ProductVariant;
use Livewire\Attributes\On;
use Livewire\Component;

class EditVariantAttributeValuesForm extends Component
{


    public $variant;
    public $productAttributesWithValues = [];
    public $selectedAttributeValues = [];


    #[On('setVariant')]
    public function setVariant(ProductVariant $variant)
    {
        $this->variant = $variant;

        $this->productAttributesWithValues = $this->variant->product->attributes ? $this->variant->product->attributes->map(function ($attribute) {
            return [
                'id' => $attribute->id,
                'name' => $attribute->name,
                'values' => $attribute->values->map(function ($value) {
                    return [
                        'id' => $value->id,
                        'value' => $value->value,
                    ];
                }),
            ];
        }) : [];

        $this->selectedAttributeValues = $this->variant->attributeValues->pluck('id', 'attribute_id')->toArray();
    }


    public function save(){
        $this->variant->attributeValues()->sync($this->selectedAttributeValues);

        $this->dispatch('closeEditVariantAttributesOffcanvas');
        $this->dispatch('refreshProductVariantsTable');
        $this->dispatch('success', 'Variant attribute values updated successfully.');
    }


    public function selectAttributeValue($attributeId, $valueId)
    {
        $this->selectedAttributeValues[$attributeId] = $valueId;
    }



    public function render()
    {
        return view('livewire.dashboard.products.edit-variant-attribute-values-form');
    }
}
