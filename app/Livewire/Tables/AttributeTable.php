<?php

namespace App\Livewire\Tables;

use App\Models\Attribute;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class AttributeTable extends PowerGridComponent
{
    public string $tableName = 'attribute-table-3j4iqq-table';

    public function setUp(): array
    {
        return [
            PowerGrid::header()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    #[On('refrsehAttributesList')]
    public function datasource(): Builder
    {
        return Attribute::with('values')->withCount('values');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('values_count')
            ->add('name')
            ->add('values', function (Attribute $model) {
                $values = '<ul style="display: flex; flex-wrap: wrap;">';
                foreach ($model->values as $value) {
                    $values .= "<li class='mb-2 table-li-item' >{$value->value}</li>";
                }
                $values .= '</ul>';
                return $values;
            })
            ->add('row_num', function ($row) {
                return $this->getRowNum($row);
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

            Column::make('Values count', 'values_count'),

            Column::make('values', 'values'),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->dispatch('openEditModal', ['attribute' => $rowId]);
    }

    public function actions(Attribute $row): array
    {
        return [
            Button::add('edit')
                ->slot('<i class="fas fa-edit"></i>')
                ->class('btn btn-primary btn-sm rounded')
                ->dispatch('edit', ['rowId' => $row->id]),
        ];
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
