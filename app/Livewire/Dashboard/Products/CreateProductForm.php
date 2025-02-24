<?php

namespace App\Livewire\Dashboard\Products;

use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\Vendor;
use App\Traits\GenerateSlugsTrait;
use App\Traits\UploadImageTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateProductForm extends Component
{
    use UploadImageTrait, GenerateSlugsTrait, WithFileUploads;
    // ========== Public Properties ==========
    public $name;
    public $description;
    public $image;
    public $sku;
    public $barcode;
    public $status = 1;
    public $vendor_id;

    public $selectedCategories = [];
    public $selectedSubCategories = [];
    public $selectedProductTypes = [];

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



    // ========== Public Methods ==========
    public function toggleCategory($categoryId)
    {
        if (in_array($categoryId, $this->selectedCategories)) {
            $this->selectedCategories = array_diff($this->selectedCategories, [$categoryId]);
        } else {
            $this->selectedCategories[] = $categoryId;
        }
    }

    public function toggleSubCategory($subCategoryId)
    {
        if (in_array($subCategoryId, $this->selectedSubCategories)) {
            $this->selectedSubCategories = array_diff($this->selectedSubCategories, [$subCategoryId]);
        } else {
            $this->selectedSubCategories[] = $subCategoryId;
        }
    }

    public function toggleProductType($productTypeId)
    {
        if (in_array($productTypeId, $this->selectedProductTypes)) {
            $this->selectedProductTypes = array_diff($this->selectedProductTypes, [$productTypeId]);
        } else {
            $this->selectedProductTypes[] = $productTypeId;
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required',
            'sku' => 'required',
            'vendor_id' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::create([
                'name' => $this->name,
                'description' => $this->description,
                'image' => $this->image ? $this->saveImage($this->image, 'products') : null,
                'sku' => $this->sku,
                'barcode' => $this->barcode,
                'vendor_id' => $this->vendor_id,
                'status' => $this->status,
                'slug' => $this->generateUniqueSlug(new Product(), $this->name, 'slug'),
            ]);

            if ($product) {
                $product->categories()->sync($this->selectedCategories);
                $product->subCategories()->sync($this->selectedSubCategories);
                $product->productTypes()->sync($this->selectedProductTypes);

                DB::commit();
                return redirect()->route('dashboard.products.index')->with('success', "Product created successfully.");
            }
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
