<?php

namespace App\Livewire\Dashboard\Products;

use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreateProductVariantForm extends Component
{
    public $product;
    #[Validate('required|string|max:255|min:3')]
    public $sku;
    #[Validate('nullable|string|max:255|min:3')]
    public $barcode;
    #[Validate('required|numeric')]
    public $compare_at_price;
    #[Validate('required|numeric')]
    public $cost_price;
    #[Validate('required|numeric')]
    public $price;
    #[Validate('nullable|string')]
    public $description;

    public $productAttributesWithValues = [];
    public $selectedAttributeValues = [];

    public function mount($product)
    {
        $this->product = $product;
        $this->sku = $this->product->sku;
        $this->productAttributesWithValues = $this->product->attributes ? $this->product->attributes->map(function ($attribute) {
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
    }


    public function selectAttributeValue($attributeId, $valueId)
    {
        $this->selectedAttributeValues[$attributeId] = $valueId;
    }

    public function save()
    {
        $this->validate();

        if ($this->isDuplicate('sku', $this->sku)) {
            $this->dispatch('error', 'Product variant with this SKU already exists.');
            return;
        }

        if ($this->isDuplicate('barcode', $this->barcode)) {
            $this->dispatch('error', 'Product variant with this barcode already exists.');
            return;
        }

        if (!$this->isValidSku()) {
            $this->dispatch('error', 'Variant SKU must contain the product SKU.');
            return;
        }

        $this->createProductVariant();

        $this->resetForm();

        return redirect()->route('dashboard.products.create-variant', $this->product->id)
            ->with('success', 'Product variant created successfully.');
    }

    private function isDuplicate($field, $value)
    {
        return ProductVariant::where($field, $value)->exists();
    }

    private function isValidSku()
    {
        return str_contains($this->sku, $this->product->sku);
    }

    private function createProductVariant()
    {
        DB::beginTransaction();
        try {
            $variant = $this->product->variants()->create([
                'sku' => $this->sku,
                'barcode' => $this->barcode,
                'compare_at_price' => $this->compare_at_price,
                'cost_price' => $this->cost_price,
                'price' => $this->price,
                'description' => $this->description,
            ]);

            // Sync attribute values only
            $variant->attributeValues()->sync($this->selectedAttributeValues);
            $variant->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('error', 'An error occurred while creating the product variant.');
        }
    }

    private function resetForm()
    {
        $this->reset([
            'sku',
            'barcode',
            'compare_at_price',
            'cost_price',
            'price',
            'description',
        ]);
    }

    public function render()
    {
        return view('livewire.dashboard.products.create-product-variant-form');
    }
}
