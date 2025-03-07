<?php

namespace App\Livewire\Dashboard\Products;

use App\Models\ProductVariant;
use App\Traits\UploadImageTrait;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateProductVariantForm extends Component
{
    use UploadImageTrait,  WithFileUploads;

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
    #[Validate('nullable|image')]
    public $image;

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

    protected function rules()
    {
        return [
            'barcode' => 'nullable|string|max:255|min:3|unique:product_variants,barcode',
        ];
    }

    public function save()
    {
        $this->validate();

        // Make sure the selectedAttributeValues must be equal to the product's attributes count
        if (count($this->selectedAttributeValues) !== $this->product->attributes->count()) {
            $this->dispatch('error', 'Please select all attribute values.');
            return;
        }

        if ($this->isDuplicate('sku', $this->sku)) {
            $this->dispatch('error', 'Product variant with this SKU already exists.');
            return;
        }


        if (!$this->isValidSku()) {
            $this->dispatch('error', "The SKU must be: 1) unique, 2) contain the product's SKU, and 3) longer than the product's SKU.");
            return;
        }

        if ($this->checkOnExistVaraintWithSameAttributeValues()) {
            $this->dispatch('error', 'Product variant with this attribute values already exists.');
            return;
        }

        DB::beginTransaction();
        try {
            $variant = $this->product->variants()->create([
                'sku' => $this->sku,
                'barcode' => $this->barcode,
                'compare_at_price' => $this->compare_at_price,
                'cost_price' => $this->cost_price,
                'price' => $this->price,
                'description' => $this->description,
                'image' => $this->image ? $this->saveImage($this->image, "products/" . $this->product->id) : null,

            ]);

            // Sync attribute values only
            $variant->attributeValues()->sync($this->selectedAttributeValues);
            $variant->save();
            DB::commit();
            $this->resetForm();
            return redirect()->route('dashboard.products.create-variant', $this->product->id)
                ->with('success', 'Product variant created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('error', $e->getMessage());
        }
    }

    private function isDuplicate($field, $value)
    {
        return ProductVariant::where($field, $value)->exists();
    }

    private function isValidSku()
    {
        return str_contains($this->sku, $this->product->sku);
    }

    private function checkOnExistVaraintWithSameAttributeValues()
    {
        $existingVariants = ProductVariant::where('product_id', $this->product->id)->get();

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
        ]);
    }

    public function render()
    {
        return view('livewire.dashboard.products.create-product-variant-form');
    }
}
