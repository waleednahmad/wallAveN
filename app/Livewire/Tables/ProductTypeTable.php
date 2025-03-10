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
            ->add('image', function ($value) {
                if (file_exists($value->image) && $value->image != null) {
                    return '<img src="' . asset($value->image) . '" alt=" Image" style="width: 90px; height: 90px; object-fit: contain;" loading="lazy" class="img-thumbnail">';
                }
                return '<img src="' . asset('dashboard/images/default.webp') . '" alt=" Image" style="width: 90px; height: 90px; object-fit: contain;" loading="lazy" class="img-thumbnail">';
            })
            ->add('row_num', function ($row) {
                return $this->getRowNum($row);
            })
            ->add('sub_category_id', function ($row) {
                return $row->subCategory ? $row->subCategory->name : '-';
            })
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'row_num'),

            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),
            Column::make('Image', 'image'),


            Column::make('status', 'status')
                ->toggleable(
                    hasPermission: auth()->check(),
                    trueLabel: '<span class="text-green-500">Yes</span>',
                    falseLabel: '<span class="text-red-500">No</span>',
                ),

            Column::make('SubCategory', 'sub_category_id'),




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

            // Button::add('toggleStatus')
            //     ->slot($row->status == 1 ? '<i class="fas fa-toggle-on"></i>' : '<i class="fas fa-toggle-off"></i>')
            //     ->class('btn btn-info btn-sm rounded')
            //     ->dispatch('toggleStatus', ['rowId' => $row->id]),
        ];
    }



    public function onUpdatedToggleable($id, $field, $value): void
    {
        ProductType::query()->find($id)->update([
            $field => $value,
        ]);
        $this->dispatch('success', 'Status updated successfully');
    }
     public function getRowNum($row): int
    {
        return $this->datasource()->pluck('id')->search($row->id) + 1;
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
