<?php

namespace App\Livewire\Tables;

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

    #[On('refreshProductTable')]
    public function datasource(): Builder
    {
        return Product::withCount(['images', 'variants'])->orderBy('created_at', 'desc');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('image', function ($value) {
                if (file_exists($value->image) && $value->image != null) {
                    return '<img src="' . asset($value->image) . '" alt=" Image" style="width: 90px; height: 90px; object-fit: contain;" loading="lazy" class="img-thumbnail">';
                }
                return '<img src="' . asset('dashboard/images/default.webp') . '" alt=" Image" style="width: 90px; height: 90px; object-fit: contain;" loading="lazy" class="img-thumbnail">';
            })
            ->add('row_num', function ($row) {
                return $this->getRowNum($row);
            })

        ;
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'row_num'),

            Column::make('image', 'image'),

            Column::make('name', 'name')
                ->searchable(),

            Column::make('SKU', 'sku')
                ->searchable(),


            Column::make('Images Count', 'images_count'),

            Column::make('Variants Count', 'variants_count'),

            Column::make('status', 'status')
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
