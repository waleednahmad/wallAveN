<?php

namespace App\Livewire\Dashboard\Products;

use App\Models\Attribute;
use App\Models\ProductVariant;
use App\Traits\UploadImageTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateProductVariantForm extends Component
{
    use UploadImageTrait,  WithFileUploads;

    public $product;
    public $main_sku;
    public $sku;
    public $barcode;
    public $compare_at_price;
    public $price;
    public $cost_price;
    public $description;
    public $image;

    public $productImages = [];
    public $selectedImageId;

    public $productAttributesWithValues = [];
    public $selectedAttributeValues = [];

    protected function rules()
    {
        return [
            'sku' => 'required|string|max:255|min:3|unique:product_variants,sku',
            'compare_at_price' => 'nullable|numeric|gt:price',
            'cost_price' => 'nullable|numeric',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            // 'image' => 'nullable|image|max:1024',
            'barcode' => 'nullable|string|max:255|min:3|unique:product_variants,barcode',
        ];
    }


    public function mount($product)
    {
        $this->product = $product;
        $this->productImages = $this->product->images ? $this->product->images->sortBy('order') : [];
        $this->sku = strtoupper($this->product->sku);
        $this->main_sku = strtoupper($this->product->sku);
        $this->productAttributesWithValues = $this->product->attributes ? $this->product->attributes->map(function ($attribute) {
            return [
                'id' => $attribute->id,
                'name' => ucfirst(strtolower($attribute->name)),
                'values' => $attribute->values->sortBy('value')->map(function ($value) {
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
        $this->regenerateSku();
    }

    public function regenerateSku()
    {
        $selectedAttributeValues = $this->productAttributesWithValues->map(function ($attribute) {
            return [
                'id' => $attribute['id'],
                'value' => $this->selectedAttributeValues[$attribute['id']] ?? null,
            ];
        })->filter(function ($attribute) {
            return !is_null($attribute['value']);
        });

        $newSku = $this->main_sku;
        foreach ($selectedAttributeValues as $attribute) {
            $attributeValue = $this->productAttributesWithValues->firstWhere('id', $attribute['id'])['values']->firstWhere('id', $attribute['value']);
            if ($attributeValue) {
                $newSku .= '-' . $attributeValue['value'];
            }
        }
        // Remove any character that is not a number, letter, or hyphen
        $this->sku = strtoupper(preg_replace('/[^A-Za-z0-9-]/', '', str_replace(' ', '', $newSku)));
    }



    public function save()
    {
        try {
            $this->validate();
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->all();
            $this->dispatch('validationFailed', $errors);
            return;
        }

        // Make sure the selectedAttributeValues must be equal to the product's attributes count
        if (count($this->selectedAttributeValues) !== $this->product->attributes->count()) {
            $this->dispatch('error', 'Please select all attribute values.');
            return;
        }

        if ($this->isDuplicate('sku', $this->sku)) {
            $this->dispatch('error', 'Product variant with this SKU already exists.');
            return;
        }

        if ($this->checkOnExistVaraintWithSameAttributeValues()) {
            $this->dispatch('error', 'Product variant with this attribute values already exists.');
            return;
        }

        DB::beginTransaction();
        try {
            $variant = ProductVariant::create([
                'product_id' => $this->product->id,
                'sku' => $this->sku,
                'barcode' => $this->barcode,
                'compare_at_price' => !empty($this->compare_at_price) ? $this->compare_at_price : null,
                'cost_price' => !empty($this->cost_price) ? $this->cost_price : null,
                'price' => !empty($this->price) ? $this->price : null,
                'description' => $this->description,
                'image' => $this->image ?? null,
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

    public function setMainImage($newMainIndex)
    {
        // Convert the string index to an integer
        $newMainIndex = (int) $newMainIndex;

        // Validate the new index
        if ($newMainIndex < 0) {
            $this->dispatch('error', 'Invalid image index.');
            return;
        }

        // Check if the selected image is already the main image
        if ($this->selectedImageId === $newMainIndex) {
            $this->deselectImage();
            return;
        }

        // Set the selected image ID
        $selectedImage = $this->productImages->where('id', $newMainIndex)->first();
        if (!$selectedImage) {
            $this->dispatch('error', 'Image not found.');
            return;
        }
        $this->image = $selectedImage['image'];
        $this->selectedImageId = $selectedImage['id'];
        $this->dispatch('success', 'Main image set successfully.');
    }

    public function deselectImage()
    {
        $this->image = null;
        $this->selectedImageId = null;
        $this->dispatch('success', 'Main image deselected successfully.');
    }


    public function setAttribute($attributeId)
    {
        $attribute = Attribute::find($attributeId);
        if (!$attribute) {
            $this->dispatch('error', 'Attribute not found.');
            return;
        }

        $this->dispatch('setAttributeValue', [
            'attribute' => $attribute,
        ]);
    }


    #[On('refreshAttributeValuesList')]
    public function refreshAttributeValuesList()
    {
        $this->productAttributesWithValues = $this->product->attributes ? $this->product->attributes->map(function ($attribute) {
            return [
                'id' => $attribute->id,
                'name' => ucfirst(strtolower($attribute->name)),
                'values' => $attribute->values->sortBy('value')->map(function ($value) {
                    return [
                        'id' => $value->id,
                        'value' => $value->value,
                    ];
                }),
            ];
        }) : [];
    }

    public function render()
    {
        return view('livewire.dashboard.products.create-product-variant-form');
    }
}
