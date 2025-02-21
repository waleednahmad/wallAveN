<?php

namespace App\Livewire\Tables;

use App\Models\Category;
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

final class SubCategoryTable extends PowerGridComponent
{
    public string $tableName = 'sub-category-table-mep6vw-table';

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

    #[On('refreshSubCategories')]
    public function datasource(): Builder
    {
        return SubCategory::with('category');
    }

    public function relationSearch(): array
    {
        return [
            'category' => [
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
            ->add('category_id', function ($row) {
                return $row->category ? $row->category->name : '';
            })
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Status', 'status'),

            Column::make('Category', 'category_id'),

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

            Filter::multiSelect('category_id', 'category_id')
                ->dataSource(Category::whereHas('subCategories')->orderBy('name')->get())
                ->optionValue('id')
                ->optionLabel('name'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit(int $rowId): void
    {
        $this->dispatch('openEditOffcanvas', ['subCategory' => $rowId]);
    }

    #[\Livewire\Attributes\On('toggleStatus')]
    public function toggleStatus(int $rowId): void
    {
        $category = SubCategory::find($rowId);
        $category->status = !$category->status;
        $category->save();
        $this->dispatch('success',  "'$category->name' status updated successfully.");
    }

    public function actions(SubCategory $row): array
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
