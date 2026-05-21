<?php

namespace App\Livewire\Dashboard;

use App\Jobs\GenerateCatalogJob;
use App\Models\Catalog;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;

class CatalogGenerator extends Component
{
    use WithFileUploads;

    // Form state
    public ?int $categoryId = null;
    public array $subcategoryIds = [];
    public string $layout = '2x3';
    public string $footerText = '';
    public ?string $catalogName = null;
    public $frontCover = null;
    public $backCover = null;

    // Data sources for the dropdowns
    public array $categories = [];
    public array $subcategories = [];

    // Recent-catalogs filters + pagination
    public string $search = '';
    public string $statusFilter = '';
    public string $layoutFilter = '';
    public int $perPage = 8;

    /** Reset pagination whenever a filter changes. */
    public function updatedSearch(): void { $this->perPage = 8; }
    public function updatedStatusFilter(): void { $this->perPage = 8; }
    public function updatedLayoutFilter(): void { $this->perPage = 8; }

    public function loadMore(): void
    {
        $this->perPage += 8;
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'statusFilter', 'layoutFilter']);
        $this->perPage = 8;
    }

    public function mount(): void
    {
        $this->footerText = 'All rights reserved © ' . date('Y');
        $this->categories = Category::orderBy('name')->get(['id', 'name'])->toArray();
    }

    public function updatedCategoryId($value): void
    {
        $this->subcategoryIds = [];

        if ($value) {
            $this->subcategories = SubCategory::where('category_id', $value)
                ->orderBy('name')
                ->get(['id', 'name'])
                ->toArray();
        } else {
            $this->subcategories = [];
        }
    }

    #[Computed]
    public function recentCatalogs()
    {
        return $this->recentCatalogsQuery()->limit($this->perPage)->get();
    }

    #[Computed]
    public function recentCatalogsTotal(): int
    {
        return $this->recentCatalogsQuery()->count();
    }

    #[Computed]
    public function hasMoreCatalogs(): bool
    {
        return $this->recentCatalogsTotal > $this->perPage;
    }

    private function recentCatalogsQuery()
    {
        $q = Catalog::query()->latest();

        if (trim($this->search) !== '') {
            $term = '%' . trim($this->search) . '%';
            $q->where(function ($qq) use ($term) {
                $qq->where('name', 'like', $term)
                   ->orWhere('footer_text', 'like', $term);
            });
        }
        if ($this->statusFilter !== '') {
            $q->where('status', $this->statusFilter);
        }
        if ($this->layoutFilter !== '') {
            $q->where('layout', $this->layoutFilter);
        }

        return $q;
    }

    #[Computed]
    public function estimatedProductsCount(): int
    {
        $query = Product::active();

        if ($this->categoryId) {
            $query->whereHas('categories', fn ($q) => $q->where('categories.id', $this->categoryId));
        }
        if (!empty($this->subcategoryIds)) {
            $query->whereHas('subCategories', fn ($q) => $q->whereIn('sub_categories.id', $this->subcategoryIds));
        }

        return $query->count();
    }

    /**
     * Whether to enable polling (only when something is in flight).
     */
    #[Computed]
    public function hasInProgress(): bool
    {
        return Catalog::whereIn('status', [Catalog::STATUS_PENDING, Catalog::STATUS_PROCESSING])->exists();
    }

    public function generate(): void
    {
        $this->validate([
            'layout' => 'required|in:2x3,3x3',
            'footerText' => 'nullable|string|max:500',
            'catalogName' => 'nullable|string|max:150',
            'frontCover' => 'nullable|image|max:4096',
            'backCover' => 'nullable|image|max:4096',
            'categoryId' => 'nullable|integer|exists:categories,id',
            'subcategoryIds' => 'nullable|array',
            'subcategoryIds.*' => 'integer|exists:sub_categories,id',
        ]);

        // Persist uploaded covers so the queued job can read them later
        $frontPath = $this->frontCover ? $this->frontCover->store('catalogs/covers', 'local') : null;
        $backPath = $this->backCover ? $this->backCover->store('catalogs/covers', 'local') : null;

        $catalog = Catalog::create([
            'user_id' => Auth::id(),
            'name' => $this->catalogName ?: ('Catalog ' . now()->format('M d, Y H:i')),
            'status' => Catalog::STATUS_PENDING,
            'layout' => $this->layout,
            'footer_text' => $this->footerText,
            'category_id' => $this->categoryId,
            'subcategory_ids' => $this->subcategoryIds ?: null,
            'front_cover_path' => $frontPath,
            'back_cover_path' => $backPath,
        ]);

        GenerateCatalogJob::dispatch($catalog->id);

        $this->reset(['frontCover', 'backCover', 'catalogName']);
        session()->flash('catalog_message', 'Catalog queued for generation. You will see it appear in the list below.');

        unset($this->recentCatalogs, $this->hasInProgress, $this->recentCatalogsTotal, $this->hasMoreCatalogs);
    }

    public function deleteCatalog(int $id): void
    {
        $catalog = Catalog::find($id);
        if (!$catalog) {
            return;
        }
        if ($catalog->isInProgress()) {
            session()->flash('catalog_error', 'Cannot delete a catalog that is still being generated.');
            return;
        }
        $catalog->delete();
        unset($this->recentCatalogs, $this->recentCatalogsTotal, $this->hasMoreCatalogs);
    }

    public function retryCatalog(int $id): void
    {
        $catalog = Catalog::find($id);
        if (!$catalog || !$catalog->isFailed()) {
            return;
        }
        $catalog->update([
            'status' => Catalog::STATUS_PENDING,
            'error_message' => null,
            'started_at' => null,
            'completed_at' => null,
        ]);
        GenerateCatalogJob::dispatch($catalog->id);
        unset($this->recentCatalogs, $this->hasInProgress, $this->recentCatalogsTotal, $this->hasMoreCatalogs);
    }

    public function render()
    {
        return view('livewire.dashboard.catalog-generator');
    }
}
