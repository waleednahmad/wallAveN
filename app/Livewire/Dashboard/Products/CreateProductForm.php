<?php

namespace App\Livewire\Dashboard\Products;

use App\Models\Vendor;
use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\ProductType;
use App\Models\SubCategory;
use Livewire\WithFileUploads;
use App\Traits\UploadImageTrait;
use Livewire\Attributes\Computed;
use App\Traits\GenerateSlugsTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateProductForm extends Component
{
    use UploadImageTrait, GenerateSlugsTrait, WithFileUploads;

    // ========== Public Properties ==========
    public $name;
    public $description;
    public $image;
    public $sku;
    public $status = 1;
    public $vendor_id;

    public $selectedCategories = [];
    public $selectedSubCategories = [];
    public $selectedProductTypes = [];
    public $selectedAttributes = [];

    public $categories = [];
    public $subCategories = [];
    public $productTypes = [];

    public $searchCategory = '';
    public $searchSubCategory = '';
    public $searchProductType = '';

    // === Gallery Handler
    public $uploadedImages = [];
    public $mainImageIndex = 0;

    // ========= Computed Properties =========
    #[Computed()]
    public function vendors()
    {
        return Vendor::active()->orderBy('name')->get();
    }

    #[Computed()]
    public function productAttributes()
    {
        return Attribute::orderBy('name')->get();
    }

    // ========== Public Methods ==========
    public function toggleSelection(&$selectionArray, $id)
    {
        if (in_array($id, $selectionArray)) {
            $selectionArray = array_diff($selectionArray, [$id]);
        } else {
            $selectionArray[] = $id;
        }
    }

    public function toggleCategory($categoryId)
    {
        $this->toggleSelection($this->selectedCategories, $categoryId);

        // reset subcategories and product types
        $this->selectedSubCategories = [];
        $this->selectedProductTypes = [];
        $this->searchSubCategory = '';
        $this->searchProductType = '';
        $this->subCategories = [];
        $this->productTypes = [];
    }

    public function toggleSubCategory($subCategoryId)
    {
        $this->toggleSelection($this->selectedSubCategories, $subCategoryId);

        // reset product types
        $this->selectedProductTypes = [];
        $this->searchProductType = '';
        $this->productTypes = [];
    }

    public function toggleProductType($productTypeId)
    {
        $this->toggleSelection($this->selectedProductTypes, $productTypeId);
    }

    public function toggleAttribute($attributeId)
    {
        $this->toggleSelection($this->selectedAttributes, $attributeId);
    }

    public function save()
    {
        $this->validate(
            [
                'name' => 'required',
                'sku' => 'required|unique:products,sku',
                'vendor_id' => 'required|exists:vendors,id',
                'selectedCategories' => 'required|array|min:1',
                'selectedAttributes' => 'required|array|min:1',
            ],
            [
                'name.required' => 'Product name is required.',
                'sku.required' => 'SKU is required.',
                'sku.unique' => 'SKU must be unique.',
                'vendor_id.required' => 'Vendor is required.',
                'selectedCategories.required' => 'You must select at least one category.',
                'selectedAttributes.required' => 'You must select at least one attribute.',
            ]
        );

        DB::beginTransaction();
        try {
            $product = Product::create([
                'name' => $this->name,
                'description' => $this->description,
                'sku' => $this->sku,
                'vendor_id' => $this->vendor_id,
                'status' => $this->status,
                'slug' => $this->generateUniqueSlug(new Product(), $this->name, 'slug'),
            ]);

            // Store the uploaded images
            if ($this->uploadedImages && count($this->uploadedImages) > 0) {
                $imagePaths = [];

                // Store all images and track their paths
                foreach ($this->uploadedImages as $index => $image) {
                    $imagePath = $this->saveImage($image, 'products/' . $product->id);
                    $imagePaths[$index] = $imagePath;

                    // Create record in the images relationship
                    $product->images()->create([
                        'image' => $imagePath,
                    ]);
                }

                // Update the product's main image if a main image index is set
                if (isset($imagePaths[$this->mainImageIndex])) {
                    $product->update(['image' => $imagePaths[$this->mainImageIndex]]);
                } else {
                    // Fallback to the first image if mainImageIndex is invalid
                    $product->update(['image' => $imagePaths[0] ?? null]);
                }
            }

            $product->categories()->sync($this->selectedCategories);
            $product->subCategories()->sync($this->selectedSubCategories);
            $product->productTypes()->sync($this->selectedProductTypes);
            $product->attributes()->sync($this->selectedAttributes);

            DB::commit();
            return redirect()->route('dashboard.products.create-variant', $product->id)->with('success', "Product created successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating product: ' . $e->getMessage());
            $this->dispatch('error', 'Something went wrong. Please try again.');
        }
    }


    // ============ Gallery handlers ============
    public function setMainImage($newMainIndex)
    {
        // Convert the string index to an integer
        $newMainIndex = (int) $newMainIndex;

        // Validate the new index
        if (!isset($this->uploadedImages[$newMainIndex])) {
            return; // Exit if the index is invalid
        }

        // If the new main image is not already the first, reorder the array
        if ($newMainIndex !== $this->mainImageIndex) {
            // Get the new main image
            $newMainImage = $this->uploadedImages[$newMainIndex];

            // Create a new array without the new main image at its current position
            $remainingImages = array_values(array_filter($this->uploadedImages, function ($key) use ($newMainIndex) {
                return $key !== $newMainIndex;
            }, ARRAY_FILTER_USE_KEY));

            // Rebuild the array with the new main image at the beginning
            $this->uploadedImages = array_merge([$newMainImage], $remainingImages);

            // Update the main image index
            $this->mainImageIndex = 0;
        }

        // Optionally, update $this->image if you need it elsewhere (as a URL)
        $this->image = $this->uploadedImages[$this->mainImageIndex]->temporaryUrl();
    }

    public function render()
    {
        // ------------ set Categories ------------
        $this->categories = Category::active()
            ->when($this->searchCategory, function ($query) {
                $query->where('name', 'like', '%' . $this->searchCategory . '%');
            })
            ->with('subCategories')
            ->orderBy('name')
            ->get();

        // ------------ set subcategories ------------
        $this->subCategories = SubCategory::whereIn('category_id', $this->selectedCategories)
            ->when($this->searchSubCategory, function ($query) {
                $query->where('name', 'like', '%' . $this->searchSubCategory . '%');
            })
            ->with('productTypes')->get();

        // ------------ set product types ------------
        $this->productTypes = ProductType::whereIn('sub_category_id', $this->selectedSubCategories)
            ->when($this->searchProductType, function ($query) {
                $query->where('name', 'like', '%' . $this->searchProductType . '%');
            })
            ->get();

        return view('livewire.dashboard.products.create-product-form');
    }
}
