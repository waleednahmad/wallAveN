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
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class RepresentativeTable extends PowerGridComponent
{
    use WithExport;
    public $fileName = '';

    public string $tableName = 'representative-table-lquyl7-table';

    public function setUp(): array
    {

        $this->showCheckBox();
        $this->fileName = 'representatives' . Carbon::now()->format('Y-m-d_H-i-s');


        return [
            PowerGrid::exportable(fileName: $this->fileName)
                ->type(Exportable::TYPE_XLS)
                ->columnWidth([
                    1 => 20,
                    2 => 35,
                    3 => 20,
                    4 => 20,
                    5 => 20,
                    6 => 20,
                    7 => 20,
                    8 => 30,
                    9 => 20,
                    10 => 20,
                    11 => 20,
                    12 => 20,
                    13 => 20,
                    14 => 20,
                    15 => 20,
                    16 => 20,
                    17 => 20,
                    18 => 20,
                    19 => 20,
                    20 => 20,
                    21 => 20,
                    22 => 20,
                    23 => 20,
                    24 => 20,
                    25 => 20,
                    26 => 20,
                ]),
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

            ->add('status_label', function ($row) {
                return $row->status ? "Active" : "Inactive";
            })
            ->add('is_approved_label', function ($row) {
                return $row->is_approved ? "Approved" : "Not Approved";
            })
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

            Column::make('Status', 'status_label')
                ->searchable()
                ->hidden()
                ->visibleInExport(true),

            Column::make('is approved', 'is_approved')
                ->visibleInExport(false),

            Column::make('is approved', 'is_approved_label')
                ->hidden()
                ->visibleInExport(true),

            Column::make('Approved at', 'approved_at')
                ->searchable(),

            Column::make('Addresss', 'address')
                ->hidden()
                ->visibleInExport(true),

            Column::make('City', 'city')
                ->hidden()
                ->visibleInExport(true),

            Column::make('State', 'state')
                ->hidden()
                ->visibleInExport(true),

            Column::make('Zip Code', 'zip_code')
                ->hidden()
                ->visibleInExport(true),

            Column::make(
                'Taxpayer Identification Number',
                'taxpayer_identification_number'
            )
                ->hidden()
                ->visibleInExport(true),

            Column::make(
                'Social Security Number',
                'social_security_number'
            )
                ->hidden()
                ->visibleInExport(true),

            Column::make('Employer Identification Number', 'employer_identification_number')
                ->hidden()
                ->visibleInExport(true),


            Column::make(
                'Bank Account Type',
                'bank_account_type'
            )
                ->hidden()
                ->visibleInExport(true),

            Column::make('Bank Routing Number', 'bank_routing_number')
                ->hidden()
                ->visibleInExport(true),


            Column::make('Bank Account Number', 'bank_account_number')
                ->hidden()
                ->visibleInExport(true),

            Column::make('status', 'status')
                ->visibleInExport(false)
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
