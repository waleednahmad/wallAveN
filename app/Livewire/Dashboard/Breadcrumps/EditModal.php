<?php

namespace App\Livewire\Dashboard\Breadcrumps;

use App\Models\PageBreadcrump;
use App\Traits\GenerateSlugsTrait;
use App\Traits\UploadImageTrait;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditModal extends Component
{
    use WithFileUploads, UploadImageTrait, GenerateSlugsTrait;

    public $pageBreadcrump;
    #[Validate('required|string|max:255')]
    public $title;
    #[Validate('nullable|string|max:255')]
    public $description;
    public $image;

    #[Validate('nullable')]
    #[Validate('max:4096', message: "The image size must be less than 4MB")]
    public $uploadImage;

    #[On('setBreadcrump')]
    public function setBreadcrump(PageBreadcrump $page)
    {
        $this->reset();
        $this->pageBreadcrump = $page;
        $this->title = $page['title'];
        $this->description = $page['description'];
        $this->image = $page['image'];
    }

    public function save()
    {
        $this->validate();
        $old_iamge = $this->pageBreadcrump->image;

        $this->pageBreadcrump->update([
            'title' => $this->title,
            'description' => $this->description,
        ]);

        if ($this->uploadImage) {
            $this->pageBreadcrump->update([
                'image' => $this->saveImage($this->uploadImage, 'pagesBreadcrump'),
            ]);

            if (file_exists($old_iamge) && $old_iamge != null) {
                unlink($old_iamge);
            }
        }


        $this->dispatch('success', 'Page updated successfully');
        $this->dispatch('closeEditModal');
        $this->dispatch('refresh');
        $this->reset();
    }



    public function render()
    {
        return view('livewire.dashboard.breadcrumps.edit-modal');
    }
}
