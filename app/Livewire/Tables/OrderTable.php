<?php

namespace App\Livewire\Tables;

use App\Models\Order;
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

final class OrderTable extends PowerGridComponent
{
    public string $tableName = 'order-table-jxuh87-table';

    public function setUp(): array
    {
        // $this->showCheckBox();

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


    #[On('refreshOrders')]
    public function datasource(): Builder
    {
        return Order::with('dealer')->orderBy('created_at', 'desc');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('company_name', function (Order $order) {
                return $order->dealer ? $order->dealer->company_name : '';
            })
            ->add('dealer_email', function (Order $order) {
                return $order->dealer ? $order->dealer->email : '';
            })
            ->add('dealer_phone', function (Order $order) {
                return $order->dealer ? $order->dealer->phone : '';
            })

            ->add('status', function (Order $order) {
                switch ($order->status) {
                    case 'pending':
                        return "<span class='badge badge-warning'>Pending</span>";
                    case 'processing':
                        return "<span class='badge badge-info'>Processing</span>";
                    case 'completed':
                        return "<span class='badge badge-success'>Completed</span>";
                    case 'declined':
                        return "<span class='badge badge-danger'>Declined</span>";
                    case 'canceled':
                        return "<span class='badge badge-danger'>Canceled</span>";
                    default:
                        return "<span class='badge badge-secondary'>Unknown</span>";
                }
            })

            ->add('total', function (Order $order) {
                return "$" . number_format($order->total, 2);
            })


            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id'),

            Column::make("Company Name", 'company_name'),
            // ->searchableRaw('company_name like ?'),

            Column::make('Dealer Email', 'dealer_email'),
            // ->searchableRaw('dealer.email like ?'),

            Column::make('Dealer Phone', 'dealer_phone'),
            // ->searchableRaw('dealer.phone like ?'),

            Column::make('status', 'status')
                ->searchable(),

            Column::make('total', 'total')
                ->searchable(),

            Column::make('quantity', 'quantity')
                ->searchable(),

            Column::make('Created at', 'created_at')
                ->searchable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::datepicker('created_at', 'created_at'),

            FIlter::select('status', 'status')
                ->dataSource([
                    [
                        'label' => 'Pending',
                        'value' => 'pending',
                    ],
                    [
                        'label' => 'Processing',
                        'value' => 'processing',
                    ],
                    [
                        'label' => 'Completed',
                        'value' => 'completed',
                    ],
                    [
                        'label' => 'Declined',
                        'value' => 'declined',
                    ],
                    [
                        'label' => 'Canceled',
                        'value' => 'canceled',
                    ],
                ])
                ->optionLabel('label')
                ->optionValue('value'),
        ];
    }

    #[\Livewire\Attributes\On('show')]
    public function show($rowId): void
    {
        $this->dispatch('showOrderDetails', ['order' => $rowId]);
    }

    #[\Livewire\Attributes\On('editStatus')]
    public function editStatus($rowId): void
    {
        $this->dispatch('showChangeStatusModal', ['order' => $rowId]);
    }

    public function actions(Order $row): array
    {
        return [
            Button::make('show')
                ->slot('<i class="fas fa-eye"></i>')
                ->class('btn btn-info btn-sm rounded')
                ->dispatch('show', ['rowId' => $row->id]),

            Button::make('editStatus')
                ->slot('<i class="fas fa-edit"></i>')
                ->class('btn btn-primary btn-sm rounded')
                ->dispatch('editStatus', ['rowId' => $row->id]),

            Button::make('print')
                ->slot('<i class="fas fa-print"></i>')
                ->class('btn btn-secondary btn-sm rounded')
                ->route('dashboard.orders.print', ['order' => $row->id], '_blank'),
        ];
    }

    public function actionRules($row): array
    {
        return [
            // Hide button edit for ID 1
            Rule::button('editStatus')
                ->when(fn($row) => $row->status == 'completed' || $row->status == 'declined' || $row->status == 'canceled')
                ->hide(),
        ];
    }
}
