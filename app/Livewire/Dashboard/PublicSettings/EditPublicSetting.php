<?php

namespace App\Livewire\Dashboard\PublicSettings;

use App\Models\PublicSetting;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class EditPublicSetting extends Component
{
    public $publicSetting;
    #[Validate('required')]
    public $key;
    #[Validate('required|numeric|min:0')]
    public $value;
    #[Validate('required')]
    public $description;

    #[On('setPublicSetting')]
    public function setPublicSetting($settingId)
    {
        $this->publicSetting = PublicSetting::find($settingId);
        $this->key = $this->publicSetting->key;
        $this->value = $this->publicSetting->value;
        $this->description = $this->publicSetting->description;
    }

    public function update()
    {
        $this->validate();

        $this->publicSetting->update([
            'value' => $this->value,
            'description' => $this->description,
        ]);

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
