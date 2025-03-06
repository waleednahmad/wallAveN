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
        return Product::withCount('images');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('image', fn($model) => $model->image ? '<img src="' . asset($model->image) . '" alt="Product Image" 
        class="img-thumbnail"   
        style="height:90px; object-fit:contain; width:90px;background-color: #f8f9fa; border-radius: 0.25rem;"
        
        >' : '<span class="badge badge-danger">No Image</span>')

        ;
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id'),

            Column::make('image', 'image'),

            Column::make('name', 'name')
                ->sortable()
                ->searchable(),

            Column::make('SKU', 'sku')
                ->sortable()
                ->searchable(),


            Column::make('Images Count', 'images_count')
                ->sortable()
                ->searchable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [];
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
            Button::add('edit')
                ->slot('<i class="fas fa-edit"></i>')
                ->class('btn btn-primary btn-sm rounded')
                ->route('dashboard.products.edit', ['product' => $row->id]),

            // Media
            Button::add('media')
                ->slot('<i class="fas fa-images"></i>')
                ->class('btn btn-secondary btn-sm rounded')
                ->dispatch('media', ['rowId' => $row->id]),

            Button::add('add-variant')
                ->slot('Variants')
                ->class('btn btn-secondary btn-sm rounded')
                ->dispatch('addVariant', ['rowId' => $row->id]),
        ];
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
