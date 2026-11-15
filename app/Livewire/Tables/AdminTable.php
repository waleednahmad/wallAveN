<?php

namespace App\Livewire\Tables;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class AdminTable extends PowerGridComponent
{
    public string $tableName = 'admin-table-ven7ss-table';

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

    #[On('reloadAdmins')]
    public function datasource(): Builder
    {
        return User::query();
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
            ->add('email')
            ->add('created_at')
            ->add('added_on', fn ($row) => $row->created_at?->format('M j, Y'));
    }

    public function columns(): array
    {
        return [
            Column::make('Name', 'name')->bodyAttribute('gr-wrap')
                ->sortable()
                ->searchable(),

            Column::make('Email', 'email')->bodyAttribute('gr-wrap')
                ->sortable()
                ->searchable(),


            Column::make('Added', 'added_on', 'created_at')->bodyAttribute('gr-nowrap')
                ->sortable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            // A few team members only: search is enough
        ];
    }


    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $amdin = User::find($rowId);
        $this->dispatch('openEditOffcanvas', ['admin' => $amdin]);
    }

    #[\Livewire\Attributes\On('updatePassword')]
    public function updatePassword($rowId): void
    {
        $amdin = User::find($rowId);
        $this->dispatch('openUpdatePasswordOffcanvas', ['admin' => $amdin]);
    }

    #[\Livewire\Attributes\On('toggleStatus')]
    public function toggleStatus($rowId): void
    {
        $admin = User::find($rowId);
        $admin->status = !$admin->status;
        $admin->save();
        $this->js('toastr.success("Status changed successfully")');
    }


    public function actions(User $row): array
    {
        return [
            Button::add('edit')
                ->slot('<i class="fas fa-edit"></i>')
                ->class('btn btn-primary btn-sm rounded')
                ->dispatch('edit', ['rowId' => $row->id]),

            Button::add('updatePassword')
                ->slot('<i class="fas fa-key"></i>')
                ->class('btn btn-warning btn-sm rounded')
                ->dispatch('updatePassword', ['rowId' => $row->id]),

            // Button::add('toggleStatus')
            //     ->slot($row->status == 1 ? '<i class="fas fa-toggle-on"></i>' : '<i class="fas fa-toggle-off"></i>')
            //     ->class('btn btn-info btn-sm rounded')
            //     ->dispatch('toggleStatus', ['rowId' => $row->id]),
        ];
    }

    public function getRowNum($row): int
    {
        return $this->datasource()->pluck('id')->search($row->id) + 1;
    }

    public function actionRules($row): array
    {
        return [
            // // Hide button edit for ID 1
            // Rule::button('edit')
            //     ->when(fn($row) => $row->id === 1)
            //     ->hide(),
            // // Rule::button('toggleStatus')
            // //     ->when(fn($row) => $row->id === 1)
            // //     ->hide(),
            // Rule::button('updatePassword')
            //     ->when(fn($row) => $row->id === 1)
            //     ->hide(),
        ];
    }
}
