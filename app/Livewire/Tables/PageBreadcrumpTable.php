<?php

namespace App\Livewire\Tables;

use App\Models\PageBreadcrump;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class PageBreadcrumpTable extends PowerGridComponent
{
    public string $tableName = 'page-breadcrump-table-hgqoq1-table';

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

    #[On('refresh')]
    public function datasource(): Builder
    {
        return PageBreadcrump::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('image', function ($value) {
                if (file_exists($value->image) && $value->image != null) {
                    return '<img src="' . asset($value->image) . '" alt=" Image" style="width: 200px; height: 90px; object-fit: contain;" loading="lazy" class="img-thumbnail">';
                }
                return '-';
            });
    }

    public function columns(): array
    {
        return [
            Column::make('page name', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Title ', 'title'),

            Column::make('Breadcrumb image', 'image')->sortable(),

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
        $this->dispatch('openEditPageModal', [
            'page' => $rowId,
        ]);
    }

    #[\Livewire\Attributes\On('remove')]
    public function remove($rowId): void
    {
        $page = PageBreadcrump::find($rowId);
        if ($page) {
            $old_iamge = $page->image;
            $page->update([
                'image' => null,
            ]);
            if (file_exists($old_iamge) && $old_iamge != null) {
                unlink($old_iamge);
            }
            $this->dispatch('success', 'Page removed successfully');
            $this->dispatch('refresh');
        }
    }

    public function actions(PageBreadcrump $row): array
    {
        return [
            Button::add('edit')
                ->slot('<i class="fas fa-edit"></i>')
                ->class('btn btn-primary btn-sm rounded')
                ->dispatch('edit', ['rowId' => $row->id]),

            // Remove image
            Button::add('remove')
                ->slot('<i class="fas fa-trash"></i>')
                ->class('btn btn-danger btn-sm rounded')
                ->dispatch('remove', ['rowId' => $row->id])
                ->confirm('Are you sure you want to remove this image?'),
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
