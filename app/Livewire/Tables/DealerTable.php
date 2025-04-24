<?php

namespace App\Livewire\Tables;

use App\Mail\DealerAccepted;
use App\Models\Dealer;
use App\Models\PriceList;
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
use Illuminate\Support\Facades\Mail;


final class DealerTable extends PowerGridComponent
{
    public string $tableName = 'dealer-table-davd0q-table';

    public function setUp(): array
    {
        $this->showCheckBox();

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
        return Dealer::with('priceList')->orderBy('created_at', 'desc');
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
            ->add('row_num', function ($row) {
                return $this->getRowNum($row);
            })
            ->add('price_list_id', function ($row) {
                return $row->priceList ? $row->priceList->name : '-';
            })
            ->add('approved_at', fn($row) => $row->approved_at ? Carbon::parse($row->approved_at)->format('Y-m-d') : '-')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'row_num'),
            
            Column::make('Name', 'company_name')
                ->searchable(),

            Column::make('Email', 'email')

                ->searchable(),

            Column::make('Address', 'address')
                ->searchable(),

            Column::make('Phone', 'phone')

                ->searchable(),

            Column::make('Price List', 'price_list_id'),

            Column::make('is approved', 'is_approved'),

            Column::make('Approved at', 'approved_at')
                ->searchable(),


            // Column::make('Resale Certificate', 'resale_certificate'),

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
        $dealerEmail = $dealer->email;
        if (!$dealer) {
            $this->js('toastr.error("Dealer not found")');
            return;
        }

        $dealer->update([
            'is_approved' => true,
            'approved_at' => now(),
        ]);

        // Update the password if the password was empty or null
        if (empty($dealer->password)) {
            $dealer->update([
                'password' => Hash::make('Password123')
            ]);
        }

        // Send email to dealer with password
        Mail::to($dealerEmail)->send(new DealerAccepted($dealer));

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
