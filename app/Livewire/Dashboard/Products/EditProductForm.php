<?php

namespace App\Livewire\Dashboard\Products;

use App\Models\Attribute;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductType;
use App\Models\SubCategory;
use App\Models\Vendor;
use App\Traits\GenerateSlugsTrait;
use App\Traits\UploadImageTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
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

    public $images = [];
    public $uploadedImages = [];
    public $imagesWithOrders = [];
    public $newImageStartOrder = 0;


    

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
        $this->images = $product->images()->orderBy('order')->get();
        $this->newImageStartOrder = $this->images->count() + 1;
    }


    public function updateImagesOrder($imagesOrder)
    {
        DB::transaction(function () use ($imagesOrder) {
            foreach ($imagesOrder as $newOrder) {
                $imageId = $newOrder['value'];
                $order = $newOrder['order'];
                ProductImage::findOrFail($imageId)->update(['order' => $order]);
            }

            $this->product->update([
                'image' => $this->product->images()->orderBy('order')->first()->image,
            ]);
        });

        $this->dispatch('success', 'Images order updated successfully.');
        $this->dispatch('refreshProductFiles');
        $this->dispatch('refreshProductTable');
        $this->images = $this->product->images()->orderBy('order')->get();

    }

    public function updateNewImagesOrder($imagesOrder)
    {
        // Create a temporary array to hold the reordered items
        $reorderedImages = [];
        $reorderedUploadedImages = [];

        // Map the new order based on the original 'value'
        foreach ($imagesOrder as $item) {
            $newPosition = $item['order'] - 1; // Convert to 0-based index
            $originalOrder = (int) $item['value']; // Original order (cast to int)

            // Find the image with the matching original order in $imagesWithOrders
            foreach ($this->imagesWithOrders as $imageItem) {
                if ($imageItem['order'] === $originalOrder) {
                    $reorderedImages[$newPosition] = [
                        'file' => $imageItem['file'],
                        'order' => $newPosition + 1, // Assign new order (1-based)
                        'name' => $imageItem['name'],
                    ];
                    $reorderedUploadedImages[$newPosition] = $imageItem['file']; // Reorder uploadedImages
                    break;
                }
            }
        }

        // Update $imagesWithOrders and $uploadedImages with the reordered arrays
        $this->imagesWithOrders = array_values($reorderedImages); // Re-index the array
        $this->uploadedImages = array_values($reorderedUploadedImages); // Re-index the array
        $this->dispatch('rerender');
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
                'vendor_id' => 'nullable|exists:vendors,id',
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

    
            // Store the uploaded images from $imagesWithOrders
            if ($this->imagesWithOrders && count($this->imagesWithOrders) > 0) {
                $imagePaths = [];
                // the new order for new image will start from the last image order +1
                $newImageStartOrder = $this->images->count() + 1;
                $n = $newImageStartOrder;

                // Store all images and track their paths
                foreach ($this->imagesWithOrders as $imageItem) {
                    $newOrder =  $n++;
                    $imagePath = $this->saveImage($imageItem['file'], 'products/' . $this->product->id);
                    $imagePaths[$imageItem['order']] = $imagePath;

                    // Create record in the images relationship
                    $this->product->images()->create([
                        'image' => $imagePath,
                        'order' => $newOrder,
                    ]);
                }
            }

            $this->product->categories()->sync($this->selectedCategories);
            $this->product->subCategories()->sync($this->selectedSubCategories);
            $this->product->productTypes()->sync($this->selectedProductTypes);
            $this->product->attributes()->sync($this->selectedAttributes);

            DB::commit();
            return redirect()->route('dashboard.products.edit', $this->product->id)->with('success', "Product updated successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('error', $e->getMessage());
        }
    }

    public function removeImage($index)
    {
        // Remove the image from the uploaded images array
        unset($this->uploadedImages[$index]);

        // Re-index the array to maintain order
        $this->uploadedImages = array_values($this->uploadedImages);

        // Remove the image from imagesWithOrders as well
        unset($this->imagesWithOrders[$index]);
        $this->imagesWithOrders = array_values($this->imagesWithOrders);


        $this->dispatch('success', 'Image removed.');
        $this->dispatch('rerender');
    }

    public function render()
    {
        // If is there any uploaded images, add order foreach one start from 1
        if ($this->uploadedImages && count($this->uploadedImages) > 0) {
            $this->imagesWithOrders = []; // Reset the array
            if (count($this->imagesWithOrders)  == 0) {
                foreach ($this->uploadedImages as $index => $image) {
                    $this->imagesWithOrders[$index] = [
                        'file' => $image, // Store the TemporaryUploadedFile object
                        'order' => $this->newImageStartOrder + $index, // Set the order
                        'name' => $image->getClientOriginalName(), // Optional: store file name
                    ];
                }
            }
        }

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
        return ProductType::whereIn('sub_category_id', $this->selectedSubCategories)
            ->when($this->searchProductType, function ($query) {
                $query->where('name', 'like', '%' . $this->searchProductType . '%');
            })
            ->get();
    }
}
