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
                    $perPage = 20,
                    $perPageValues = [10, 20, 50, 100, 0]
                )
                ->showRecordCount(),
        ];
    }


    #[On('refreshOrders')]
    public function datasource(): Builder
    {
        return Order::with('dealer');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('dealer_name', function (Order $order) {
                return $order->dealer->name;
            })
            ->add('dealer_email', function (Order $order) {
                return $order->dealer->email;
            })
            ->add('dealer_phone', function (Order $order) {
                return $order->dealer->phone;
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
                return "$ " . number_format($order->total, 2);
            })


            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id'),

            Column::make("Dealer Name", 'dealer_name'),
            // ->searchableRaw('dealer_name like ?'),

            Column::make('Dealer Email', 'dealer_email'),
            // ->searchableRaw('dealer.email like ?'),

            Column::make('Dealer Phone', 'dealer_phone'),
            // ->searchableRaw('dealer.phone like ?'),

            Column::make('status', 'status')
                ->searchable(),

            Column::make('total', 'total')
                ->sortable()
                ->searchable(),

            Column::make('quantity', 'quantity')
                ->sortable()
                ->searchable(),

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
