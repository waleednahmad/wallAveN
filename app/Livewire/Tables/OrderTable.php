<?php

namespace App\Livewire\Tables;

use App\Models\Dealer;
use App\Models\Order;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Number;
use Livewire\Attributes\On;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class OrderTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'order-table-jxuh87-table';
    public $fileName = '';


    public function setUp(): array
    {
        $this->showCheckBox();
        $this->fileName = 'orders_' . Carbon::now()->format('Y-m-d_H-i-s');

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


    #[On('refreshOrders')]
    public function datasource(): Builder
    {
        return Order::join('dealers', 'orders.dealer_id', '=', 'dealers.id')
            ->select([
                'orders.id',
                'orders.status',
                'orders.total',
                'orders.created_at',
                'orders.quantity',
                'dealers.company_name as company_name',
                'dealers.email as dealer_email',
                'dealers.phone as dealer_phone',
            ])
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
            // ->add('company_name', function (Order $order) {
            //     return $order->dealer ? $order->dealer->company_name : '';
            // })
            // ->add('dealer_email', function (Order $order) {
            //     return $order->dealer ? $order->dealer->email : '';
            // })
            // ->add('dealer_phone', function (Order $order) {
            //     return $order->dealer ? $order->dealer->phone : '';
            // })

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

            ->add('status_label', function (Order $order) {
                return $order->status;
            })

            ->add('total', function (Order $order) {
                return "$" . number_format($order->total, 2);
            })

            ->add('created_at_formatted', function (Order $order) {
                return $order->created_at->format('Y-m-d H:i:s');
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

            Column::make('Status', 'status')
                ->searchable()
                ->visibleInExport(false),

            Column::make('Status', 'status_label')
                ->searchable()
                ->hidden()
                ->visibleInExport(true),

            Column::make('Total', 'total')
                ->withSum('total', header: false, footer: true)
                ->searchable(),

            Column::make('Quantity', 'quantity')
                ->withSum('quantity', header: false, footer: true)
                ->searchable(),

            Column::make('Created at', 'created_at')
                ->searchable()
                ->visibleInExport(false),

            Column::make('Created at', 'created_at_formatted' , 'created_at')
                ->searchable()
                ->hidden()
                ->visibleInExport(true),

            Column::action('Action')
        ];
    }

    public function summarizeFormat(): array
    {
        return [
            'total.{sum,avg,count,min,max}' => fn($value) => "$" . number_format($value, 2),
            'quantity.{sum,avg,count,min,max}' => fn($value) => Number::format($value),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::datepicker('created_at', 'created_at'),

            Filter::select('status', 'status')
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

            Filter::select('company_name', 'company_name')
                ->dataSource(
                    Dealer::whereHas('orders')->get(['id', 'company_name'])
                        ->map(function ($dealer) {
                            return [
                                'label' => $dealer->company_name,
                                'value' => $dealer->company_name,
                            ];
                        })
                )
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

            // New PDF action button
            Button::make('pdf')
                ->slot('<i class="fas fa-file-pdf"></i>')
                ->class('btn btn-danger btn-sm rounded')
                ->route('dashboard.orders.pdf', ['order' => $row->id], '_blank'),
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
