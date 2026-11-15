<?php

namespace App\Livewire\Tables;

use App\Mail\DealerAccepted;
use App\Models\Dealer;
use App\Models\PriceList;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use Illuminate\Support\Facades\Mail;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class DealerTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'dealer-table-davd0q-table';

    /** Tab on the dealers page (?tab=approved). "applications" shows review cards instead of this table. */
    #[Url(as: 'tab', except: 'all')]
    public string $tab = 'all';
    public $fileName = '';

    public function setUp(): array
    {
        $this->showCheckBox();
        $this->fileName = 'dealers_' . Carbon::now()->format('Y-m-d_H-i-s');

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

    public function header(): array
    {
        return [
            Button::add('setPriceList')
                ->slot('Set Price List (<span x-text="window.pgBulkActions.count(\'' . $this->tableName . '\')"></span>)')
                ->class('btn btn-success')
                ->dispatch('setPriceList.' . $this->tableName, []),
        ];
    }

    #[On('setPriceList.{tableName}')]
    public function setPriceList(): void
    {
        $selectedDealers = "window.pgBulkActions.get('" . $this->tableName . "')";
        $this->js("
            if ({$selectedDealers}.length === 0) {
                toastr.error('No dealers selected');
                return;
            }
            Livewire.dispatch('openPriceListModal', { dealers: {$selectedDealers} });
        ");
    }

    #[On('reloadDealers')]
    public function datasource(): Builder
    {
        return Dealer::with('priceList')
            ->when($this->tab === 'approved', fn ($q) => $q->where('is_approved', true))
            ->when($this->tab === 'inactive', fn ($q) => $q->where('status', false))
            ->when($this->tab === 'declined', fn ($q) => $q->whereNotNull('rejected_at'))
            ->orderBy('created_at', 'desc');
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
                    return "<span class='badge badge-success'>Yes</span>";
                } else {
                    return "<span class='badge badge-danger'>No</span>";
                }
            })
            ->add('resale_certificate', function ($row) {
                if ($row->resale_certificate && file_exists(public_path($row->resale_certificate))) {
                    return "<a href='" . asset($row->resale_certificate) . "' target='_blank'>View</a>";
                } else {
                    return '-';
                }
            })
            // Company name with the email under it (one column instead of two, so the table fits the screen)
            ->add('dealer_cell', function ($row) {
                return '<div class="adm-cell-main">' . e($row->company_name ?: ($row->name ?: '-')) . '</div>'
                    . ($row->email ? '<div class="adm-cell-sub">' . e($row->email) . '</div>' : '');
            })
            ->add('row_num', function ($row) {
                return $this->getRowNum($row);
            })
            ->add('price_list_id', function ($row) {
                return $row->priceList ? $row->priceList->name : '-';
            })
            ->add('approved_at', fn($row) => $row->approved_at ? Carbon::parse($row->approved_at)->format('Y-m-d') : '')


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

            Column::make('Dealer', 'dealer_cell', 'company_name')->bodyAttribute('gr-wrap')
                ->sortable()
                ->visibleInExport(false),

            // Hidden on screen (shown inside the Dealer column) but still searched and exported.
            Column::make('Name', 'company_name')
                ->searchable()
                ->hidden()
                ->visibleInExport(true),

            Column::make('Email', 'email')
                ->searchable()
                ->hidden()
                ->visibleInExport(true),

            Column::make('Status', 'status_label')
                ->hidden()
                ->visibleInExport(true),

            // Kept for search and export; the full address is in the dealer details (eye button).
            Column::make('Address', 'address')->bodyAttribute('gr-wrap')
                ->searchable()
                ->hidden()
                ->visibleInExport(true),

            Column::make('Phone', 'phone')
                ->bodyAttribute('gr-nowrap')
                ->searchable(),

            Column::make('Price List', 'price_list_id'),

            Column::make('Approved', 'is_approved')
                ->visibleInExport(false),

            Column::make('is approved', 'is_approved_label')
                ->hidden()
                ->visibleInExport(true),

            Column::make('Approved on', 'approved_at')
                ->bodyAttribute('gr-nowrap')
                ->searchable(),


            // Column::make('Resale Certificate', 'resale_certificate'),

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

            Filter::datepicker('approved_at', 'approved_at'),

            Filter::boolean('is_approved')
                ->label('approved', 'not approved'),

            Filter::boolean('status')
                ->label('active', 'inactive'),

            Filter::multiSelect('price_list_id', 'price_list_id')
                ->dataSource(PriceList::whereHas('dealers')->get())
                ->optionValue('id')
                ->optionLabel('name'),
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
            $this->dispatch('error', 'Dealer not found');
            return;
        }

        // Approval, default password (if none) and the "Dealer Accepted" email live on the model
        $dealer->approveApplication();

        $this->dispatch('success', 'Dealer approved and emailed');
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

            // Button::add('toggleStatus')
            //     ->slot($row->status == 1 ? '<i class="fas fa-toggle-on"></i>' : '<i class="fas fa-toggle-off"></i>')
            //     ->class('btn btn-info btn-sm rounded')
            //     ->dispatch('toggleStatus', ['rowId' => $row->id]),


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

    public function onUpdatedToggleable($id, $field, $value): void
    {
        Dealer::query()->find($id)->update([
            $field => $value,
        ]);
        $this->dispatch('success', 'Status updated successfully');
    }

    public function getRowNum($row): int
    {
        return $this->datasource()->pluck('id')->search($row->id) + 1;
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
