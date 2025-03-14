<?php

namespace App\Livewire\Tables;

use App\Models\PublicSetting;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class PublicSettingTable extends PowerGridComponent
{
    public string $tableName = 'public-setting-table-w8ybpm-table';

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

    #[On('refreshPublicSettingTable')]
    public function datasource(): Builder
    {
        return PublicSetting::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('value', function ($value) {
                if ($value->key == 'activate vendor') {
                    return $value->value == 1 ? "<span class='badge badge-success'>Active</span>" : "<span class='badge badge-danger'>Inactive</span>";
                } elseif ($value->key == "main logo") {
                    return "<img src='" . asset($value->value) . "' alt='Logo' class='img-thumbnail' style='width: 90px;'>";
                } else {
                    return $value->value;
                }
            })
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Key', 'key')
                ->searchable(),

            Column::make('Value', 'value')
                ->searchable(),

            Column::make('Description', 'description'),


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
        $this->dispatch('openEditOffcanvas', ['settingId' => $rowId]);
    }
    public function actions(PublicSetting $row): array
    {
        return [
            Button::add('edit')
                ->slot('<i class="fas fa-edit"></i>')
                ->class('btn btn-primary btn-sm rounded')
                ->dispatch('edit', ['rowId' => $row->id]),

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
