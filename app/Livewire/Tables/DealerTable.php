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

    // Newest first by default; clicking a column header sorts by that column instead
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

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
        $sales = fn ($q) => $q->whereIn('orders.status', ['pending', 'processing', 'completed']);

        return Dealer::with('priceList')
            ->withCount(['orders as orders_count' => $sales])
            ->withSum(['orders as sales_total' => $sales], 'total')
            ->withMax(['orders as last_order_at' => $sales], 'created_at')
            ->when($this->tab === 'approved', fn ($q) => $q->where('is_approved', true))
            ->when($this->tab === 'inactive', fn ($q) => $q->where('status', false))
            ->when($this->tab === 'declined', fn ($q) => $q->whereNotNull('rejected_at'));
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            // Approved date, or where the application stands
            ->add('approved_cell', function ($row) {
                if ($row->is_approved) {
                    return $row->approved_at ? Carbon::parse($row->approved_at)->format('M j, Y') : '<span class="badge badge-success">Approved</span>';
                }
                return $row->rejected_at ? '<span class="badge badge-danger">Declined</span>' : '<span class="badge badge-warning">Waiting</span>';
            })
            ->add('orders_count')
            ->add('sales_cell', fn ($row) => $row->sales_total ? '$' . number_format((float) $row->sales_total, 0) : '<span class="text-muted">—</span>')
            ->add('sales_plain', fn ($row) => number_format((float) $row->sales_total, 2, '.', ''))
            // When they last ordered; 90+ days is flagged so quiet dealers stand out
            ->add('last_order_cell', function ($row) {
                if (!$row->last_order_at) {
                    return '<span class="text-muted">Never</span>';
                }
                $d = Carbon::parse($row->last_order_at);
                $txt = '<span title="' . $d->format('M j, Y') . '">' . $d->diffForHumans() . '</span>';
                return $d->lt(now()->subDays(90)) ? '<span class="adm-quiet">' . $txt . '</span>' : $txt;
            })
            ->add('last_order_plain', fn ($row) => $row->last_order_at ? Carbon::parse($row->last_order_at)->format('Y-m-d') : '')
            ->add('resale_certificate', function ($row) {
                if ($row->resale_certificate && file_exists(public_path($row->resale_certificate))) {
                    return "<a href='" . asset($row->resale_certificate) . "' target='_blank'>View</a>";
                } else {
                    return '-';
                }
            })
            // Company name with the email under it (one column instead of two, so the table fits the screen)
            ->add('dealer_cell', function ($row) {
                $place = trim(implode(', ', array_filter([$row->city, $row->state])));
                $sub = array_map(fn ($v) => '<span class="adm-nowrap">' . e($v) . '</span>', array_filter([$row->email, $place]));
                return '<div class="adm-cell-main">' . e($row->company_name ?: ($row->name ?: '-')) . '</div>'
                    . ($sub ? '<div class="adm-cell-sub">' . implode(' · ', $sub) . '</div>' : '');
            })
            ->add('price_list_id', function ($row) {
                return $row->priceList ? $row->priceList->name : '-';
            })
            ->add('approved_at_plain', fn($row) => $row->approved_at ? Carbon::parse($row->approved_at)->format('Y-m-d') : '')


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

            Column::make('City', 'city')->searchable()->hidden()->visibleInExport(true),
            Column::make('State', 'state')->searchable()->hidden()->visibleInExport(true),

            Column::make('Price list', 'price_list_id'),

            Column::make('Orders', 'orders_count')->headerAttribute('text-end')->bodyAttribute('text-end')
                ->sortUsing(fn ($query, $dir) => $query->orderBy('orders_count', $dir)),

            Column::make('Sales', 'sales_cell', 'sales_total')->headerAttribute('text-end')->bodyAttribute('gr-nowrap text-end')
                ->sortUsing(fn ($query, $dir) => $query->orderBy('sales_total', $dir))
                ->visibleInExport(false),
            Column::make('Sales', 'sales_plain')->hidden()->visibleInExport(true),

            Column::make('Last order', 'last_order_cell', 'last_order_at')->bodyAttribute('gr-nowrap')
                ->sortUsing(fn ($query, $dir) => $query->orderByRaw('last_order_at IS NULL, last_order_at ' . ($dir === 'asc' ? 'asc' : 'desc')))
                ->visibleInExport(false),
            Column::make('Last order', 'last_order_plain')->hidden()->visibleInExport(true),

            Column::make('Approved', 'approved_cell', 'approved_at')->bodyAttribute('gr-nowrap')
                ->sortable()
                ->visibleInExport(false),

            Column::make('is approved', 'is_approved_label')
                ->hidden()
                ->visibleInExport(true),

            Column::make('Approved on', 'approved_at_plain')
                ->hidden()
                ->visibleInExport(true),

            // Column::make('Resale Certificate', 'resale_certificate'),

            Column::make('Status', 'status')
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
            Filter::multiSelect('price_list_id', 'price_list_id')
                ->dataSource(PriceList::whereHas('dealers')->get())
                ->optionValue('id')
                ->optionLabel('name'),

            Filter::select('state', 'state')
                ->dataSource(
                    Dealer::whereNotNull('state')->where('state', '!=', '')
                        ->distinct()->orderBy('state')->pluck('state')
                        ->map(fn ($st) => ['value' => $st, 'label' => $st])->values()->all()
                )
                ->optionValue('value')
                ->optionLabel('label'),

            // Who to call: ordered recently, gone quiet, or never ordered
            Filter::select('last_order_cell', 'activity')
                ->dataSource([
                    ['value' => 'recent', 'label' => 'Ordered in the last 90 days'],
                    ['value' => 'quiet', 'label' => 'No order in 90+ days'],
                    ['value' => 'never', 'label' => 'Never ordered'],
                ])
                ->optionValue('value')
                ->optionLabel('label')
                ->builder(function (Builder $query, $value) {
                    $sales = fn ($q) => $q->whereIn('orders.status', ['pending', 'processing', 'completed']);
                    $since = now()->subDays(90);
                    return match ($value) {
                        'recent' => $query->whereHas('orders', fn ($q) => $sales($q)->where('orders.created_at', '>=', $since)),
                        'quiet' => $query->whereHas('orders', $sales)->whereDoesntHave('orders', fn ($q) => $sales($q)->where('orders.created_at', '>=', $since)),
                        'never' => $query->whereDoesntHave('orders', $sales),
                        default => $query,
                    };
                }),

            Filter::datepicker('approved_at', 'approved_at'),
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
