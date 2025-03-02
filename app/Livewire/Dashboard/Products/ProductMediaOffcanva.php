<?php

namespace App\Livewire\Dashboard\Products;

use App\Models\Product;
use App\Traits\UploadImageTrait;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductMediaOffcanva extends Component
{
    use WithFileUploads, UploadImageTrait;
    public $product;
    public $uploadedImages = [];
    public $images = [];

    #[On('setProductFiles')]
    public function setProductFiles($product)
    {
        $this->product = Product::with('images')->findOrFail($product);
        $this->images = $this->product->images->toArray();
    }

    public function save()
    {
        $this->validate([
            'uploadedImages' => 'nullable|array|min:1',
            'uploadedImages.*' => 'required|image',
        ]);

        foreach ($this->uploadedImages as $image) {
            $this->product->images()->create([
                'image' => $this->saveImage($image, 'products/' . $this->product->id),
            ]);
        }

        $this->refreshProductData();
    }

    public function removeImage($image)
    {
        $file = $this->product->images()->findOrFail($image);
        $filePath = public_path($file->image);

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $file->delete();
        $this->refreshProductData();
    }


    public function refreshProductData()
    {
        $this->product->load('images');
        $this->dispatch('refreshProductTable');
        $this->dispatch('refreshProductFiles');
    }

    #[On('refreshProductFiles')]
    public function refreshProductFiles()
    {
        $this->images = $this->product->images->toArray();
    }

    public function render()
    {
        return view('livewire.dashboard.products.product-media-offcanva');
    }
}
