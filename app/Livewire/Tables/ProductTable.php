<?php

namespace App\Livewire\Tables;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class ProductTable extends PowerGridComponent
{
    public string $tableName = 'product-table-0ykt8i-table';

    public function setUp(): array
    {
        // $this->showCheckBox();

        return [
            PowerGrid::header()
                ->showSoftDeletes()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage(
                    $perPage = 25,
                    $perPageValues = [10, 25, 50, 100, 0]
                )
                ->showRecordCount(),
        ];
    }

    // Newest first by default; clicking a column header sorts by that column instead
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    #[On('refreshProductTable')]
    public function datasource(): Builder
    {
        return Product::query()
            ->withCount(['images', 'variants'])
            ->withMin('variants', 'price')
            ->withMax('variants', 'price')
            ->with('categories:id,name');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        $money = fn ($n) => '$' . number_format((float) $n, 2);

        return PowerGrid::fields()
            // Plain fields are escaped by PowerGrid; columns not listed here would print raw HTML
            ->add('name')
            ->add('sku')
            ->add('image', function ($value) {
                $src = ($value->image && file_exists($value->image)) ? thumb($value->image, 240) : asset('dashboard/images/default.webp');
                return '<img src="' . $src . '" alt="" loading="lazy" class="img-thumbnail">';
            })
            // Name with SKU and categories underneath
            ->add('product_cell', function ($row) {
                $sub = array_filter([$row->sku ? 'SKU ' . e($row->sku) : null, e($row->categories->pluck('name')->join(', '))]);
                return '<div class="adm-cell-main">' . e($row->name ?: '-') . '</div>'
                    . ($sub ? '<div class="adm-cell-sub">' . implode(' · ', $sub) . '</div>' : '');
            })
            ->add('categories_list', fn ($row) => $row->categories->pluck('name')->join(', '))
            ->add('price_range', function ($row) use ($money) {
                if ($row->variants_min_price === null) {
                    return '<span class="text-muted">—</span>';
                }
                return (float) $row->variants_min_price === (float) $row->variants_max_price
                    ? $money($row->variants_min_price)
                    : $money($row->variants_min_price) . ' – ' . $money($row->variants_max_price);
            })
            ->add('price_plain', fn ($row) => $row->variants_min_price === null ? '' : $row->variants_min_price . ($row->variants_max_price != $row->variants_min_price ? ' - ' . $row->variants_max_price : ''))
            // A product with no photos or no variants can't sell: flag it
            ->add('images_cell', fn ($row) => $row->images_count ? (string) $row->images_count : '<span class="badge badge-warning" title="No photos">0</span>')
            ->add('variants_cell', fn ($row) => $row->variants_count ? (string) $row->variants_count : '<span class="badge badge-warning" title="No variants: dealers can\'t order it">0</span>')
            ->add('needs_label', fn () => '')
            ->add('added_on', fn ($row) => $row->created_at?->format('M j, Y'));
    }

    public function columns(): array
    {
        $alias = fn (string $col) => fn ($query, $dir) => $query->orderBy($col, $dir);

        return [
            Column::make('Photo', 'image')->visibleInExport(false),

            Column::make('Product', 'product_cell', 'name')->bodyAttribute('gr-wrap')
                ->sortable()
                ->visibleInExport(false),

            // Shown inside the Product column; kept for search and export
            Column::make('Name', 'name')->searchable()->hidden()->visibleInExport(true),
            Column::make('SKU', 'sku')->searchable()->hidden()->visibleInExport(true),
            Column::make('Category', 'categories_list')->hidden()->visibleInExport(true),
            Column::make('Needs attention', 'needs_label')->hidden()->visibleInExport(false),

            Column::make('Price', 'price_range', 'variants_min_price')
                ->headerAttribute('text-end')->bodyAttribute('gr-nowrap text-end')
                ->sortUsing($alias('variants_min_price'))
                ->visibleInExport(false),
            Column::make('Price', 'price_plain')->hidden()->visibleInExport(true),

            Column::make('Photos', 'images_cell', 'images_count')
                ->headerAttribute('text-end')->bodyAttribute('text-end')
                ->sortUsing($alias('images_count')),

            Column::make('Variants', 'variants_cell', 'variants_count')
                ->headerAttribute('text-end')->bodyAttribute('text-end')
                ->sortUsing($alias('variants_count')),

            Column::make('Added', 'added_on', 'created_at')->bodyAttribute('gr-nowrap')
                ->sortable(),

            Column::make('Status', 'status')
                ->toggleable(
                    hasPermission: auth()->check(),
                    trueLabel: '<span class="text-green-500">Yes</span>',
                    falseLabel: '<span class="text-red-500">No</span>',
                ),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::boolean('status')
                ->label('active', 'inactive'),

            Filter::multiSelect('categories_list', 'category_ids')
                ->dataSource(Category::orderBy('name')->get(['id', 'name']))
                ->optionValue('id')
                ->optionLabel('name')
                ->builder(fn (Builder $query, $values) => $query->when(
                    filled($values),
                    fn ($q) => $q->whereHas('categories', fn ($c) => $c->whereIn('categories.id', (array) $values))
                )),

            // "What needs fixing" in the catalog (same checks as Catalog health on the home page)
            Filter::select('needs_label', 'needs')
                ->dataSource([
                    ['value' => 'no_photos', 'label' => 'No photos'],
                    ['value' => 'no_variants', 'label' => 'No variants'],
                    ['value' => 'no_price', 'label' => 'No price'],
                ])
                ->optionValue('value')
                ->optionLabel('label')
                ->builder(fn (Builder $query, $value) => match ($value) {
                    'no_photos' => $query->where(fn ($q) => $q->whereNull('products.image')->orWhere('products.image', '')),
                    'no_variants' => $query->doesntHave('variants'),
                    'no_price' => $query->whereDoesntHave('variants', fn ($v) => $v->whereNotNull('price')->where('price', '>', 0)),
                    default => $query,
                }),
        ];
    }

    #[\Livewire\Attributes\On('addVariant')]
    public function addVariant($rowId): void
    {
        $this->redirect(route('dashboard.products.create-variant', $rowId));
    }

    #[\Livewire\Attributes\On('media')]
    public function openMediaOffcanvas($rowId): void
    {
        $this->dispatch('openMediaOffcanvas', ['product' => $rowId]);
    }

    //  soft delete
    #[On('delete')]
    public function delete($id): void
    {
        $product = Product::query()->find($id);
        if ($product) {
            $product->update([
                'deleted_by' => auth()->user()->id,
            ]);
            $product->variants()->update([
                'deleted_by' => auth()->user()->id,
            ]);

            // Delete the product and all of it's variants 
            $product->delete();
            $product->variants()->delete();
            $this->dispatch('success', 'Product deleted successfully');
        } else {
            $this->dispatch('error', 'Product not found');
        }
    }

    #[On('restore')]
    public function restore($id): void
    {
        $product = Product::withTrashed()->find($id);
        if ($product) {
            $product->restore();
            $product->variants()->withTrashed()->restore();
            $this->dispatch('success', 'Product restored successfully');
        } else {
            $this->dispatch('error', 'Product not found');
        }
    }

    public function actions(Product $row): array
    {
        return [

            Button::add('view')
                ->slot('<i class="fas fa-eye"></i>')
                ->class('btn btn-info btn-sm rounded')
                ->route('frontend.product', ['slug' => $row->slug], '_blank'),

            Button::add('edit')
                ->slot('<i class="fas fa-edit"></i>')
                ->class('btn btn-primary btn-sm rounded')
                ->route('dashboard.products.edit', ['product' => $row->id]),

            // Media
            // Button::add('media')
            //     ->slot('<i class="fas fa-images"></i>')
            //     ->class('btn btn-secondary btn-sm rounded')
            //     ->dispatch('media', ['rowId' => $row->id]),

            Button::add('add-variant')
                ->slot('Variants')
                ->class('btn btn-secondary btn-sm rounded')
                ->dispatch('addVariant', ['rowId' => $row->id]),

            Button::add('delete')
                ->slot('<i class="fas fa-trash"></i>')
                ->class('btn btn-danger btn-sm rounded')
                ->dispatch('delete', ['id' => $row->id])
                ->confirm('Are you sure you want to delete this product?'),

            // restore
            Button::add('restore')
                ->slot('<i class="fas fa-undo"></i>')
                ->class('btn btn-success btn-sm rounded')
                ->dispatch('restore', ['id' => $row->id])
                ->confirm('Are you sure you want to restore this product?'),
        ];
    }

    public function getRowNum($row): int
    {
        return $this->datasource()->pluck('id')->search($row->id) + 1;
    }

    public function onUpdatedToggleable($id, $field, $value): void
    {
        Product::query()->find($id)->update([
            $field => $value,
        ]);
        $this->dispatch('success', 'Status updated successfully');
    }

    public function actionRules($row): array
    {
        return [
            Rule::button('view')
                ->when(fn($row) => $row->trashed())
                ->hide(),

            Rule::button('edit')
                ->when(fn($row) => $row->trashed())
                ->hide(),

            Rule::button('add-variant')
                ->when(fn($row) => $row->trashed())
                ->hide(),

            Rule::button('delete')
                ->when(fn($row) => $row->trashed())
                ->hide(),

            Rule::button('restore')
                ->when(fn($row) => !$row->trashed())
                ->hide(),
        ];
    }
}
