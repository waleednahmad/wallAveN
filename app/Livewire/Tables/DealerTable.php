<?php

namespace App\Livewire\Tables;

use App\Models\Dealer;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\On;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Facades\Rule;


final class DealerTable extends PowerGridComponent
{
    public string $tableName = 'dealer-table-davd0q-table';

    public function setUp(): array
    {

        return [
            PowerGrid::header()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage(
                    $perPage = 20,
                    $perPageValues = [10, 20, 50, 100, 0]
                )
                ->showRecordCount(),
        ];
    }

    #[On('reloadDealers')]
    public function datasource(): Builder
    {
        return Dealer::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('is_approved', function ($row) {
                if ($row->is_approved) {
                    return "<span class='badge badge-success'>yes</span>";
                } else {
                    return "<span class='badge badge-danger'>no</span>";
                }
            })
            ->add('status', function ($row) {
                if ($row->status) {
                    return "<span class='badge badge-success'>active</span>";
                } else {
                    return "<span class='badge badge-danger'>inactive</span>";
                }
            })

            ->add('resale_certificate', function ($row) {
                if ($row->resale_certificate && file_exists(public_path($row->resale_certificate))) {
                    return "<a href='" . asset($row->resale_certificate) . "' target='_blank'>View</a>";
                } else {
                    return '-';
                }
            })

            ->add('approved_at', fn($row) => $row->approved_at ? Carbon::parse($row->approved_at)->format('Y-m-d') : '-')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id'),

            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Email', 'email')
                ->sortable()
                ->searchable(),

            Column::make('Phone', 'phone')
                ->sortable()
                ->searchable(),

            Column::make('is approved', 'is_approved'),

            Column::make('Approved at', 'approved_at')
                ->sortable()
                ->searchable(),

            Column::make('Status', 'status'),

            Column::make('Resale Certificate', 'resale_certificate'),

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

            Filter::datepicker('approved_at', 'approved_at'),

            Filter::boolean('is_approved')
                ->label('approved', 'not approved'),

            Filter::boolean('status')
                ->label('active', 'inactive'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }

    #[\Livewire\Attributes\On('approve')]
    public function approve($rowId): void
    {
        $dealer = Dealer::find($rowId);
        if (!$dealer) {
            $this->js('toastr.error("Dealer not found")');
            return;
        }

        $dealer->update([
            'is_approved' => true,
            'approved_at' => now(),
            'password' => Hash::make('Password123')
        ]);
        $this->js('toastr.success("Dealer approved successfully")');
        $this->refresh();
    }

    #[\Livewire\Attributes\On('updatePassword')]
    public function updatePassword($rowId): void
    {
        $dealer = Dealer::find($rowId);
        $this->dispatch('openUpdatePasswordOffcanvas', ['dealer' => $dealer]);
    }

    #[\Livewire\Attributes\On('toggleStatus')]
    public function toggleStatus($rowId): void
    {
        $dealer = Dealer::find($rowId);
        $dealer->update([
            'status' => !$dealer->status
        ]);
        $this->js('toastr.success("Status changed successfully")');
    }

    #[\Livewire\Attributes\On('show')]
    public function show($rowId): void
    {
        $dealer = Dealer::find($rowId);
        $this->dispatch('openShowOffcanvas', ['dealer' => $dealer]);
    }




    public function actions(Dealer $row): array
    {
        return [
            Button::make('approve')
                ->slot('<i class="fas fa-check"></i>')
                ->class('btn btn-success btn-sm rounded')
                ->dispatch('approve', ['rowId' => $row->id]),

            Button::add('toggleStatus')
                ->slot($row->status == 1 ? '<i class="fas fa-toggle-on"></i>' : '<i class="fas fa-toggle-off"></i>')
                ->class('btn btn-info btn-sm rounded')
                ->dispatch('toggleStatus', ['rowId' => $row->id]),


            Button::add('updatePassword')
                ->slot('<i class="fas fa-key"></i>')
                ->class('btn btn-warning btn-sm rounded')
                ->dispatch('updatePassword', ['rowId' => $row->id]),

            Button::add('show')
                ->slot('<i class="fas fa-eye"></i>')
                ->class('btn btn-primary btn-sm rounded')
                ->dispatch('show', ['rowId' => $row->id]),
        ];
    }

    public function actionRules($row): array
    {
        return [
            // Hide button edit for ID 1
            Rule::button('approve')
                ->when(fn($row) => $row->is_approved)
                ->hide(),
        ];
    }
}
