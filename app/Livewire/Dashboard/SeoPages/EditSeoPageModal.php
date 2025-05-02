<?php

namespace App\Livewire\Dashboard\SeoPages;

use Livewire\Component;
use App\Models\SeoPage;
use Livewire\Attributes\On;

class EditSeoPageModal extends Component
{
    public $seoPage;
    public $name;
    public $title;
    public $meta_desc;
    public $keywords;

    #[On('setPage')]
    public function setSeoPage(SeoPage $page)
    {
        $this->seoPage = $page;
        $this->name = $this->seoPage->name;
        $this->title = $this->seoPage->title;
        $this->meta_desc = $this->seoPage->meta_desc;
        $this->keywords = $this->seoPage->keywords;
    }

    public function update()
    {
        $this->validate([
            'title' => 'nullable|string',
            'meta_desc' => 'nullable|string',
            'keywords' => 'nullable|string',
        ]);
        $this->seoPage->update([
            'title' => $this->title,
            'meta_desc' => $this->meta_desc,
            'keywords' => $this->keywords,
        ]);
        $this->dispatch('success', 'SEO Page updated successfully');
        $this->dispatch('refreshSeoPageTable');
        $this->dispatch('closeModal');
        $this->reset();
    }

    public function render()
    {
        return view('livewire.dashboard.seo-pages.edit-seo-page-modal');
    }
}
