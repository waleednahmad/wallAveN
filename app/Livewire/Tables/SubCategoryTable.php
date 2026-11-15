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
        return SubCategory::with('category')->withCount('products');
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
            // Plain fields are escaped by PowerGrid; columns not listed here would print raw HTML
            ->add('name')
            ->add('products_count')
            ->add('id')
            ->add('image', function ($value) {
                if (file_exists($value->image) && $value->image != null) {
                    return '<img src="' . thumb($value->image, 240) . '" alt=" Image" style="width: 90px; height: 90px; object-fit: contain;" loading="lazy" class="img-thumbnail">';
                }
                return '<img src="' . asset('dashboard/images/default.webp') . '" alt=" Image" style="width: 90px; height: 90px; object-fit: contain;" loading="lazy" class="img-thumbnail">';
            })
            ->add('category_id', function ($row) {
                return $row->category ? $row->category->name : '';
            })
            ->add('created_at');
    }

    public function columns(): array
    {
        return [

            Column::make('Image', 'image'),

            Column::make('Name', 'name')->bodyAttribute('gr-wrap')
                ->sortable()
                ->searchable(),





            Column::make('Category', 'category_id'),

            Column::make('Products', 'products_count')->headerAttribute('text-end')->bodyAttribute('text-end')
                ->sortUsing(fn ($query, $dir) => $query->orderBy('products_count', $dir)),

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

            // Button::add('toggleStatus')
            //     ->slot($row->status == 1 ? '<i class="fas fa-toggle-on"></i>' : '<i class="fas fa-toggle-off"></i>')
            //     ->class('btn btn-info btn-sm rounded')
            //     ->dispatch('toggleStatus', ['rowId' => $row->id]),
        ];
    }

    public function onUpdatedToggleable($id, $field, $value): void
    {
        SubCategory::query()->find($id)->update([
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
