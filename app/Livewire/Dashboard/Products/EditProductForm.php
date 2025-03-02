<?php

namespace App\Livewire\Dashboard\Products;

use App\Models\Attribute;
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

class EditProductForm extends Component
{
    use UploadImageTrait, GenerateSlugsTrait, WithFileUploads;

    // ========== Public Properties ==========
    public $product;
    public $name;
    public $description;
    public $image;
    public $sku;
    public $status;
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

    // ========== Lifecycle Hooks ==========
    public function mount(Product $product)
    {
        $this->product = $product;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->sku = $product->sku;
        $this->status = $product->status;
        $this->vendor_id = $product->vendor_id;
        $this->selectedCategories = $product->categories->pluck('id')->toArray();
        $this->selectedSubCategories = $product->subCategories->pluck('id')->toArray();
        $this->selectedProductTypes = $product->productTypes->pluck('id')->toArray();
        $this->selectedAttributes = $product->attributes->pluck('id')->toArray();
    }

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
    public function toggleCategory($categoryId)
    {
        if (in_array($categoryId, $this->selectedCategories)) {
            $this->selectedCategories = array_diff($this->selectedCategories, [$categoryId]);
        } else {
            $this->selectedCategories[] = $categoryId;
        }

        $this->resetSubCategoriesAndProductTypes();
    }

    public function toggleSubCategory($subCategoryId)
    {
        if (in_array($subCategoryId, $this->selectedSubCategories)) {
            $this->selectedSubCategories = array_diff($this->selectedSubCategories, [$subCategoryId]);
        } else {
            $this->selectedSubCategories[] = $subCategoryId;
        }

        $this->resetProductTypes();
    }

    public function toggleProductType($productTypeId)
    {
        if (in_array($productTypeId, $this->selectedProductTypes)) {
            $this->selectedProductTypes = array_diff($this->selectedProductTypes, [$productTypeId]);
        } else {
            $this->selectedProductTypes[] = $productTypeId;
        }
    }

    public function toggleAttribute($attributeId)
    {
        if (in_array($attributeId, $this->selectedAttributes)) {
            $this->selectedAttributes = array_diff($this->selectedAttributes, [$attributeId]);
        } else {
            $this->selectedAttributes[] = $attributeId;
        }
    }

    public function save()
    {
        $this->validate(
            [
                'name' => 'required',
                'sku' => 'required|unique:products,sku,' . $this->product->id,
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
            $old_image = $this->product->image;
            $this->product->update([
                'name' => $this->name,
                'description' => $this->description,
                'sku' => $this->sku,
                'vendor_id' => $this->vendor_id,
                'status' => $this->status,
                'slug' => $this->generateUniqueSlug($this->product, $this->name, 'slug'),
            ]);

            if ($this->image) {
                $this->product->update([
                    'image' => $this->saveImage($this->image, 'products'),
                ]);

                if (file_exists($old_image) && $old_image) {
                    unlink($old_image);
                }
            }

            $this->product->categories()->sync($this->selectedCategories);
            $this->product->subCategories()->sync($this->selectedSubCategories);
            $this->product->productTypes()->sync($this->selectedProductTypes);
            $this->product->attributes()->sync($this->selectedAttributes);

            DB::commit();
            return redirect()->route('dashboard.products.index')->with('success', "Product updated successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('error', $e->getMessage());
        }
    }

    public function render()
    {
        $this->categories = $this->getCategories();
        $this->subCategories = $this->getSubCategories();
        $this->productTypes = $this->getProductTypes();

        return view('livewire.dashboard.products.edit-product-form');
    }

    // ========== Private Methods ==========
    private function resetSubCategoriesAndProductTypes()
    {
        $this->selectedSubCategories = [];
        $this->selectedProductTypes = [];
        $this->searchSubCategory = '';
        $this->searchProductType = '';
        $this->subCategories = [];
        $this->productTypes = [];
    }

    private function resetProductTypes()
    {
        $this->selectedProductTypes = [];
        $this->searchProductType = '';
        $this->productTypes = [];
    }

    private function getCategories()
    {
        return Category::active()
            ->when($this->searchCategory, function ($query) {
                $query->where('name', 'like', '%' . $this->searchCategory . '%');
            })
            ->with('subCategories')
            ->orderBy('name')
            ->get();
    }

    private function getSubCategories()
    {
        return SubCategory::whereIn('category_id', $this->selectedCategories)
            ->when($this->searchSubCategory, function ($query) {
                $query->where('name', 'like', '%' . $this->searchSubCategory . '%');
            })
            ->with('productTypes')->get();
    }

    private function getProductTypes()
    {
        return SubCategory::whereIn('id', $this->selectedSubCategories)
            ->when($this->searchProductType, function ($query) {
                $query->where('name', 'like', '%' . $this->searchProductType . '%');
            })
            ->with('productTypes')->get();
    }
}
