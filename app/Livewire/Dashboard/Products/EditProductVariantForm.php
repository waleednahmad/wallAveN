<?php

namespace App\Livewire\Dashboard\Products;

use App\Models\Attribute;
use App\Models\ProductImage;
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
    public $main_sku;
    public $sku;
    public $barcode;
    public $compare_at_price;
    public $price;
    public $cost_price;
    public $description;
    public $image;

    public $productAttributesWithValues = [];
    public $selectedAttributeValues = [];

    public $productImages = [];
    public $selectedImageId;

    protected function rules()
    {
        return [
            'sku' => 'required|string|max:255|min:3|unique:product_variants,sku,' . $this->variant?->id,
            'barcode' => 'nullable|string|max:255|min:3|unique:product_variants,barcode,' . $this->variant?->id,
            'compare_at_price' => 'nullable|numeric|gt:price',
            'cost_price' => 'nullable|numeric',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ];
    }


    #[On('setVariant')]
    public function setVariant(ProductVariant $variant)
    {
        $this->variant = $variant;
        $this->product = $variant->product;
        $this->sku = strtoupper($variant->sku);
        $this->main_sku = strtoupper($variant->sku);
        $this->barcode = $variant->barcode;
        $this->compare_at_price = $variant->compare_at_price;
        $this->cost_price = $variant->cost_price;
        $this->price = $variant->price;
        $this->description = $variant->description;
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
        $this->selectedAttributeValues = $variant->attributeValues->mapWithKeys(function ($value) {
            return [$value->attribute_id => $value->id];
        })->toArray();


        $this->productImages = $this->product->images ? $this->product->images->sortBy('order') : [];
        $this->selectedImageId = ProductImage::where('product_id', $this->product->id)
            ->where('image', $variant->image)
            ->first()
            ?->id;
        $this->dispatch('setVariantData');
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
            // Deselect the current main image
            $this->deselectImage();
            return;
        }

        // Set the selected image ID
        $selectedImage = $this->productImages->where('id', $newMainIndex)->first();
        if (!$selectedImage) {
            $this->dispatch('error', 'Image not found.');
            return;
        }

        // Update the main image
        $this->variant->update([
            'image' => $selectedImage['image'],
        ]);
        $this->image = $selectedImage['image'];
        $this->selectedImageId = $selectedImage['id'];
        $this->dispatch('success', 'Main image set successfully.');
        $this->dispatch('refreshProductVariantsTable');
    }

    public function deselectImage()
    {
        $this->image = null;
        $this->selectedImageId = null;
        $this->variant->update([
            'image' => null,
        ]);
        $this->dispatch('refreshProductVariantsTable');
        $this->dispatch('success', 'Main image deselected successfully.');
    }


    public function selectAttributeValue($attributeId, $valueId)
    {
        $this->selectedAttributeValues[$attributeId] = $valueId;
    }



    public function save()
    {
        $this->validate();

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
                'compare_at_price' => !empty($this->compare_at_price) ? $this->compare_at_price : null,
                'cost_price' => !empty($this->cost_price) ? $this->cost_price : null,
                'price' => !empty($this->price) ? $this->price : null,
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


    public function setEditAttribute($attributeId)
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
        return view('livewire.dashboard.products.edit-product-variant-form');
    }
}
