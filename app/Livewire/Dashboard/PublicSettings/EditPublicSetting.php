<?php

namespace App\Livewire\Dashboard\PublicSettings;

use App\Models\PublicSetting;
use App\Traits\GenerateSlugsTrait;
use App\Traits\UploadImageTrait;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditPublicSetting extends Component
{
    use UploadImageTrait, GenerateSlugsTrait, WithFileUploads;

    public $publicSetting;
    #[Validate('required')]
    public $key;
    #[Validate('required')]
    public $value;
    #[Validate('required')]
    public $description;
    public $type;
    #[Validate('nullable|image')]
    public $image;
    public $default_image;

    #[On('setPublicSetting')]
    public function setPublicSetting(PublicSetting $settingId)
    {
        $this->publicSetting = $settingId;
        $this->key = $this->publicSetting->key;
        $this->value = $this->publicSetting->value;
        $this->description = $this->publicSetting->description;
        $this->type = $this->publicSetting->type;
        $this->default_image = asset('assets/img/logo.webp');
    }

    public function resetImage()
    {
        $old_image = $this->publicSetting->value;

        $this->publicSetting->update([
            'value' => $value,
        ]);

        if ($old_image && file_exists(public_path($old_image)) && !str_contains($old_image, 'assets/img/logo.webp')) {
            unlink(public_path($old_image));
        }
    }

    public function update()
    {
        $this->validate();

        $value = $this->value;

        if ($this->type == 'image' && $this->image) {
            $old_image = $this->publicSetting->value;

            $value = $this->saveImage($this->image, 'public-settings');

            $this->publicSetting->update([
                'value' => $value,
                'description' => $this->description,
            ]);

            if ($old_image && file_exists(public_path($old_image)) && !str_contains($old_image, 'assets/img/logo.webp')) {
                unlink(public_path($old_image));
            }

            return redirect()->route('dashboard.public-settings.index')->with('success', 'Public setting updated successfully');
        } else {
            $this->publicSetting->update([
                'value' => $value,
                'description' => $this->description,
            ]);
        }


        $this->dispatch('success', 'Public setting updated successfully');
        $this->dispatch('closeEditOffcanvas');
        $this->dispatch('refreshPublicSettingTable');
        $this->reset();
    }
    public function render()
    {
        return view('livewire.dashboard.public-settings.edit-public-setting');
    }
}
