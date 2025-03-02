<?php

namespace App\Livewire\Dashboard\Products;

use App\Models\Vendor;
use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use App\Models\Attribute;
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
                'image' => $this->image ? $this->saveImage($this->image, 'products') : null,
                'sku' => $this->sku,
                'vendor_id' => $this->vendor_id,
                'status' => $this->status,
                'slug' => $this->generateUniqueSlug(new Product(), $this->name, 'slug'),
            ]);

            $product->categories()->sync($this->selectedCategories);
            $product->subCategories()->sync($this->selectedSubCategories);
            $product->productTypes()->sync($this->selectedProductTypes);
            $product->attributes()->sync($this->selectedAttributes);

            DB::commit();
            return redirect()->route('dashboard.products.index')->with('success', "Product created successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating product: ' . $e->getMessage());
            $this->dispatch('error', 'Something went wrong. Please try again.');
        }
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
        $this->productTypes = SubCategory::whereIn('id', $this->selectedSubCategories)
            ->when($this->searchProductType, function ($query) {
                $query->where('name', 'like', '%' . $this->searchProductType . '%');
            })
            ->with('productTypes')->get();

        return view('livewire.dashboard.products.create-product-form');
    }
}
