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
        return Product::withCount(['images', 'variants']);
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
                ->sortable()
                ->searchable(),

            Column::make('SKU', 'sku')
                ->sortable()
                ->searchable(),


            Column::make('Images Count', 'images_count')
                ->sortable(),

            Column::make('Variants Count', 'variants_count')
                ->sortable(),

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
    /*
    public function actionRules($row): array
    {
       return [
            // Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($row) => $row->id === 1)
                ->hide(),
        ];
    }
    */
}
