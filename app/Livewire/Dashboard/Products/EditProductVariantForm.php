<?php

namespace App\Livewire\Dashboard\Products;

use App\Models\ProductVariant;
use App\Traits\UploadImageTrait;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditProductVariantForm extends Component
{
    use UploadImageTrait, WithFileUploads;

    public $product, $variant;
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
    #[Validate('nullable|image')]
    public $image;

    public $productAttributesWithValues = [];
    public $selectedAttributeValues = [];

    #[On('setVariant')]
    public function setVariant(ProductVariant $variant)
    {
        $this->variant = $variant;
        $this->product = $variant->product;
        $this->sku = $variant->sku;
        $this->barcode = $variant->barcode;
        $this->compare_at_price = $variant->compare_at_price;
        $this->cost_price = $variant->cost_price;
        $this->price = $variant->price;
        $this->description = $variant->description;
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
        $this->selectedAttributeValues = $variant->attributeValues->mapWithKeys(function ($value) {
            return [$value->attribute_id => $value->id];
        })->toArray();

        $this->dispatch('setVariantData');
    }

    public function selectAttributeValue($attributeId, $valueId)
    {
        $this->selectedAttributeValues[$attributeId] = $valueId;
    }

    protected function rules()
    {
        return [
            'sku' => 'required|string|max:255|min:3|unique:product_variants,sku,' . $this->variant?->id,
            'barcode' => 'nullable|string|max:255|min:3|unique:product_variants,barcode,' . $this->variant?->id,
            'compare_at_price' => 'nullable|numeric',
            'cost_price' => 'nullable|numeric',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'image' => 'nullable|image',
        ];
    }

    public function save()
    {
        $this->validate();

        if (!$this->isValidSku()) {
            $this->dispatch('error', "The SKU must be: 1) unique, 2) contain the product's SKU, and 3) longer than the product's SKU.");
            return;
        }

        if (count($this->selectedAttributeValues) !== $this->product->attributes->count()) {
            $this->dispatch('error', 'Please select all attribute values.');
            return;
        }

        if ($this->checkOnExistVaraintWithSameAttributeValues()) {
            $this->dispatch('error', 'Product variant with this attribute values already exists.');
            return;
        }


        DB::beginTransaction();
        try {
            $this->variant->update([
                'sku' => $this->sku,
                'barcode' => $this->barcode,
                'compare_at_price' => $this->compare_at_price,
                'cost_price' => $this->cost_price,
                'price' => $this->price,
                'description' => $this->description,
                // 'image' => $this->image ? $this->saveImage($this->image, "products/" . $this->product->id) : $this->variant->image,
            ]);

            // Sync attribute values only
            $this->variant->attributeValues()->sync($this->selectedAttributeValues);
            DB::commit();
            $this->resetForm();
            return redirect()->route('dashboard.products.create-variant', $this->product->id)
                ->with('success', 'Product variant updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('error', $e->getMessage());
        }
    }

    private function isValidSku()
    {
        return str_contains($this->sku, $this->product->sku);
    }


    private function checkOnExistVaraintWithSameAttributeValues()
    {
        $existingVariants = ProductVariant::where('product_id', $this->product->id)
            ->where('id', '!=', $this->variant->id)
            ->get();

        foreach ($existingVariants as $variant) {
            $variantAttributeValues = $variant->attributeValues->pluck('id')->toArray();
            sort($variantAttributeValues);
            $selectedAttributeValues = $this->selectedAttributeValues;
            sort($selectedAttributeValues);

            if ($variantAttributeValues == $selectedAttributeValues) {
                return true;
            }
        }

        return false;
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
            'image',
        ]);
    }

    public function render()
    {
        return view('livewire.dashboard.products.edit-product-variant-form');
    }
}
