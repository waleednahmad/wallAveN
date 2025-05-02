<?php

namespace App\Livewire\Tables;

use App\Models\SeoPage;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use Livewire\Attributes\On;

final class SeoPageTable extends PowerGridComponent
{
    public string $tableName = 'seo-page-table-s9mire-table';

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

    #[On('refreshSeoPageTable')]
    public function datasource(): Builder
    {
        return SeoPage::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('title');
    }

    public function columns(): array
    {
        return [
            Column::make('Name', 'name')->searchable(),
            Column::make('Title', 'title')->searchable(),
            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->dispatch('openEditSeoPageModal', ['page' => $rowId]);
    }

    public function actions(SeoPage $row): array
    {
        return [
            Button::add('edit')
                ->slot('<i class="fas fa-edit"></i>')
                ->class('btn btn-primary btn-sm rounded')
                ->dispatch('edit', ['rowId' => $row->id])
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
