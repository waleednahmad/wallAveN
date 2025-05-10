<?php

namespace App\Livewire\Tables;

use App\Models\Dealer;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class AboundedCheckoutTable extends PowerGridComponent
{
    public string $tableName = 'abounded-checkout-table-54oczu-table';

    public function setUp(): array
    {
        return [
            PowerGrid::header()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Dealer::whereHas('cartTemps', function ($query) {
            $query->whereNull('representative_id')
                ->whereNull('admin_id');
        })
            ->join('cart_temps', function ($join) {
                $join->on('dealers.id', '=', 'cart_temps.dealer_id')
                    ->whereNull('cart_temps.representative_id')
                    ->whereNull('cart_temps.admin_id');
            })
            ->select([
                'dealers.*',
                DB::raw('SUM(cart_temps.total) as total_cart_amount'),
                DB::raw('SUM(cart_temps.quantity) as total_cart_quantity')
            ])
            ->groupBy('dealers.id')
            ->distinct();
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
            ->add('total_cart_amount', function ($row) {
                return "$" . $row->total_cart_amount;
            })
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

            Column::make('Phone', 'phone')
                ->searchable(),

            Column::make('total_cart_quantity', 'total_cart_quantity')
                ->sortable()
                ->searchable(),

            Column::make('total_cart_amount', 'total_cart_amount')
                ->sortable()
                ->searchable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [];
    }

    #[On('show')]
    public function show($id)
    {
        $this->dispatch('previewAboundedCheckout', ['dealer' => $id]);
    }


    public function actions(Dealer $row): array
    {
        return [
            Button::add('show')
                ->slot('<i class="fas fa-eye"></i>')
                ->class('btn btn-primary btn-sm rounded')
                ->dispatch('show', ['id' => $row->id]),
        ];
    }

    public function getRowNum($row): int
    {
        return $this->datasource()->pluck('id')->search($row->id) + 1;
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
