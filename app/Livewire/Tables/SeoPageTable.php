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
        // Lengths Google shows before cutting text off
        $check = function (?string $text, int $min, int $max): string {
            $n = mb_strlen(trim((string) $text));
            if ($n === 0) {
                return '<span class="badge badge-danger">Missing</span>';
            }
            if ($n > $max) {
                return '<span class="badge badge-warning" title="' . $n . ' characters">Too long · ' . $n . '</span>';
            }
            if ($n < $min) {
                return '<span class="badge badge-secondary" title="' . $n . ' characters">Short · ' . $n . '</span>';
            }
            return '<span class="badge badge-success">Good · ' . $n . '</span>';
        };

        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('page_name', fn ($row) => e(ucwords(str_replace(['_', '-'], ' ', (string) $row->name))))
            ->add('title', fn ($row) => $row->title ? e(\Illuminate\Support\Str::limit($row->title, 90)) : '<span class="text-muted">Not set</span>')
            ->add('title_check', fn ($row) => $check($row->title, 20, $row->name === 'home' ? 60 : 60 - mb_strlen(' | ' . (getWebsiteTitle() ?: config('app.name')))))
            ->add('desc_check', fn ($row) => $check($row->meta_desc, 70, 160));
    }

    public function columns(): array
    {
        return [
            Column::make('Page', 'page_name', 'name')->bodyAttribute('gr-nowrap')->searchable(),
            Column::make('Title', 'title')->bodyAttribute('gr-wrap')->searchable(),
            Column::make('Title length', 'title_check')->bodyAttribute('gr-nowrap'),
            Column::make('Description', 'desc_check')->bodyAttribute('gr-nowrap'),
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
