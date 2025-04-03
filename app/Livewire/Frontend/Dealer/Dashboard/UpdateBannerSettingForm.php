<?php

namespace App\Livewire\Frontend\Dealer\Dashboard;

use Livewire\Attributes\Validate;
use Livewire\Component;

class UpdateBannerSettingForm extends Component
{
    // $table->string('text')->default('Golden Rugs – Exclusive Deals Just for You');
    // $table->string('text_color')->default('#000000');
    // $table->string('bg_color')->default('#f1c55e');
    #[Validate('required|string')]
    public $text;
    #[Validate('required|string|max:255')]
    public $text_color;
    #[Validate('required|string|max:255')]
    public $bg_color;
    #[Validate('required|numeric|min:1|max:100')]
    public $fake_sale_percentage;

    public function mount()
    {
        $this->fake_sale_percentage = auth('dealer')->user()->fake_sale_percentage ?? 0;
        if (auth('dealer')->user()->bannerSetting) {
            $this->text = auth('dealer')->user()->bannerSetting->text;
            $this->text_color = auth('dealer')->user()->bannerSetting->text_color;
            $this->bg_color = auth('dealer')->user()->bannerSetting->bg_color;
        } else {
            auth('dealer')->user()->bannerSetting()->create([
                'text' => 'Golden Rugs – Exclusive Deals Just for You',
                'text_color' => '#000000',
                'bg_color' => '#f1c55e',
            ]);
        }
    }


    public function updateBannerSettings()
    {
        $this->validate();
        auth('dealer')->user()->bannerSetting()->updateOrCreate([], [
            'text' => $this->text,
            'text_color' => $this->text_color,
            'bg_color' => $this->bg_color,
        ]);

        auth('dealer')->user()->update([
            'fake_sale_percentage' => $this->fake_sale_percentage,
        ]);

        return redirect()->route('dealer.profile')->with('success', 'Banner settings updated successfully.');
    }
    public function render()
    {
        return view('livewire.frontend.dealer.dashboard.update-banner-setting-form');
    }
}
