<?php

namespace App\Livewire\Tables;

use App\Models\ProductType;
use App\Models\SubCategory;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class ProductTypeTable extends PowerGridComponent
{
    public string $tableName = 'product-type-table-5eu49x-table';

    public function setUp(): array
    {
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

    #[On('refreshProductTypes')]
    public function datasource(): Builder
    {
        return ProductType::with('subCategory');
    }

    public function relationSearch(): array
    {
        return [
            'subCategory' => [
                'name',
            ],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('status', function ($row) {
                if ($row->status) {
                    return "<span class='badge badge-success'>active</span>";
                } else {
                    return "<span class='badge badge-danger'>inactive</span>";
                }
            })
            ->add('image', fn($model) => $model->image ? '<img src="' . asset($model->image) . '" alt="Product Image" 
            class="img-thumbnail"   
            style="height:90px; object-fit:contain; width:90px;background-color: #f8f9fa; border-radius: 0.25rem;"
            
            >' : '<span class="badge badge-danger">No Image</span>')
            ->add('sub_category_id', function ($row) {
                return $row->subCategory ? $row->subCategory->name : '';
            })
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),
            Column::make('Image', 'image'),


            Column::make('Status', 'status'),

            Column::make('SubCategory', 'sub_category_id'),

            Column::make('Created at', 'created_at')
                ->sortable()
                ->searchable(),


            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::datepicker('created_at', 'created_at'),

            Filter::boolean('status')
                ->label('active', 'inactive'),

            Filter::multiSelect('sub_category_id', 'sub_category_id')
                ->dataSource(SubCategory::whereHas('productTypes')->orderBy('name')->get())
                ->optionValue('id')
                ->optionLabel('name'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit(int $rowId): void
    {
        $this->dispatch('openEditOffcanvas', ['productType' => $rowId]);
    }


    #[\Livewire\Attributes\On('toggleStatus')]
    public function toggleStatus($rowId): void
    {
        $productType = ProductType::find($rowId);
        $productType->status = !$productType->status;
        $productType->save();
        $this->dispatch('success', 'Product type status updated successfully.');
    }

    public function actions(ProductType $row): array
    {
        return [
            Button::add('edit')
                ->slot('<i class="fas fa-edit"></i>')
                ->class('btn btn-primary btn-sm rounded')
                ->dispatch('edit', ['rowId' => $row->id]),

            Button::add('toggleStatus')
                ->slot($row->status == 1 ? '<i class="fas fa-toggle-on"></i>' : '<i class="fas fa-toggle-off"></i>')
                ->class('btn btn-info btn-sm rounded')
                ->dispatch('toggleStatus', ['rowId' => $row->id]),
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
