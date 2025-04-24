<?php

namespace App\Livewire\Tables;

use App\Mail\RepresentativeAccepted;
use App\Models\Representative;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\On;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Facades\Rule;


final class RepresentativeTable extends PowerGridComponent
{
    public string $tableName = 'representative-table-lquyl7-table';

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

    #[On('reloadRepresentatives')]
    public function datasource(): Builder
    {
        return Representative::orderBy('created_at', 'desc');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('row_num', function ($row) {
                return $this->getRowNum($row);
            })
            ->add('is_approved', function ($row) {
                if ($row->is_approved) {
                    return "<span class='badge badge-success'>Yes</span>";
                } else {
                    return "<span class='badge badge-danger'>No</span>";
                }
            })
            ->add('approved_at', fn($row) => $row->approved_at ? Carbon::parse($row->approved_at)->format('Y-m-d') : '-')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'row_num'),
            Column::make('Name', 'name')
                ->searchable(),
            Column::make('Email', 'email')
                ->searchable(),
            Column::make('Phone', 'phone')
                ->searchable(),
            Column::make('is approved', 'is_approved'),

            Column::make('Approved at', 'approved_at')

                ->searchable(),
            Column::make('status', 'status')
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
            Filter::datepicker('created_at', 'created_at'),

            Filter::boolean('status')
                ->label('active', 'inactive')
        ];
    }


    #[\Livewire\Attributes\On('toggleStatus')]
    public function toggleStatus($rowId): void
    {
        $row = Representative::find($rowId);
        $row->status = !$row->status;
        $row->save();
        $this->js('toastr.success("Status changed successfully")');
    }

    #[\Livewire\Attributes\On('updatePassword')]
    public function updatePassword($rowId): void
    {
        $representative = Representative::find($rowId);
        $this->dispatch('openUpdatePasswordOffcanvas', ['representative' => $representative]);
    }

    #[\Livewire\Attributes\On('approve')]
    public function approve($rowId): void
    {
        $rep = Representative::find($rowId);
        if (!$rep) {
            $this->js('toastr.error("representative not found")');
            return;
        }

        $rep->update([
            'is_approved' => true,
            'approved_at' => now(),
        ]);

        Mail::to($rep->email)->send(new RepresentativeAccepted($rep));

        $this->js('toastr.success("representative approved successfully")');
        $this->refresh();
    }

    public function actions(Representative $row): array
    {
        return [
            // Button::add('toggleStatus')
            //     ->slot($row->status == 1 ? '<i class="fas fa-toggle-on"></i>' : '<i class="fas fa-toggle-off"></i>')
            //     ->class('btn btn-info btn-sm rounded')
            //     ->dispatch('toggleStatus', ['rowId' => $row->id]),

            Button::make('approve')
                ->slot('<i class="fas fa-check"></i>')
                ->class('btn btn-success btn-sm rounded')
                ->dispatch('approve', ['rowId' => $row->id]),

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


    public function getRowNum($row): int
    {
        return $this->datasource()->pluck('id')->search($row->id) + 1;
    }

    public function onUpdatedToggleable($id, $field, $value): void
    {
        Representative::query()->find($id)->update([
            $field => $value,
        ]);
        $this->dispatch('success', 'Status updated successfully');
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
