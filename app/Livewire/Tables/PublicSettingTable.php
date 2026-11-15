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
        $switches = ['activate vendor', 'show become a rep in the menu', 'activate-large-menu'];
        $images = ['main logo', 'favicon'];

        return PowerGrid::fields()
            ->add('id')
            // "activate-large-menu" -> "Activate large menu" (the key itself stays as the code expects it)
            ->add('setting_name', fn ($row) => $row->key === 'toggle show category & shop pages'
                ? 'Catalog for visitors'
                : e(ucfirst(str_replace(['-', '_'], ' ', (string) $row->key))))
            ->add('value', function ($row) use ($switches, $images) {
                if ($row->key === 'toggle show category & shop pages') {
                    return match ((string) $row->value) {
                        '1' => "<span class='badge badge-success'>Open · prices after login</span>",
                        '2' => "<span class='badge badge-success'>Open with prices</span>",
                        default => "<span class='badge badge-secondary'>Closed</span>",
                    };
                }
                                if (in_array($row->key, $switches, true)) {
                    return $row->value == 1 ? "<span class='badge badge-success'>On</span>" : "<span class='badge badge-secondary'>Off</span>";
                }
                if (in_array($row->key, $images, true)) {
                    return "<img src='" . e(asset($row->value)) . "' alt='' class='img-thumbnail' loading='lazy'>";
                }
                // Escaped: values can hold code (e.g. the analytics snippet) that must not run in the admin
                $text = trim((string) $row->value);
                if ($text === '') {
                    return '<span class="text-muted">Not set</span>';
                }
                return str_contains($text, '<')
                    ? '<code class="adm-code">' . e(\Illuminate\Support\Str::limit($text, 80)) . '</code>'
                    : e(\Illuminate\Support\Str::limit($text, 120));
            })
            ->add('description', fn ($row) => e((string) $row->description))
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Setting', 'setting_name', 'key')->bodyAttribute('gr-wrap')
                ->searchable(),

            Column::make('Value', 'value')->bodyAttribute('gr-wrap')
                ->searchable(),

            Column::make('Description', 'description')->bodyAttribute('gr-wrap'),


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
