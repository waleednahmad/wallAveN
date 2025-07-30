<?php

namespace App\Livewire\Tables;

use App\Models\Dealer;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class AdminDealersAbandonedCartsTable extends PowerGridComponent
{
    public int $adminId;
    public string $tableName = 'admin-dealers-abandoned-carts-table';

    public function setUp(): array
    {
        return [
            PowerGrid::header()->showSearchInput(),
            PowerGrid::footer()->showPerPage()->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Dealer::whereHas('cartTemps', function ($query) {
                $query->whereNotNull('admin_id')
                      ->whereNull('representative_id');
            })
            ->whereHas('cartTemps', function ($query) {
                $query->where('admin_id', $this->adminId)
                      ->whereNull('representative_id');
            })
            ->join('cart_temps', function ($join) {
                $join->on('dealers.id', '=', 'cart_temps.dealer_id')
                    ->where('cart_temps.admin_id', $this->adminId)
                    ->whereNull('cart_temps.representative_id');
            })
            ->select([
                'dealers.*',
                DB::raw('SUM(cart_temps.total) as total_cart_amount'),
                DB::raw('SUM(cart_temps.quantity) as total_cart_quantity'),
                DB::raw('MAX(cart_temps.created_at) as last_cart_temp_date')
            ])
            ->groupBy('dealers.id')
            ->distinct();
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('row_num', function ($row) {
                return $this->getRowNum($row);
            })
            ->add('total_cart_amount', function ($row) {
                return "$" . number_format($row->total_cart_amount, 2);
            })
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'row_num'),
            Column::make('Name', 'company_name')->searchable(),
            Column::make('Quantity', 'total_cart_quantity')->sortable(),
            Column::make('Total', 'total_cart_amount')->sortable(),
            Column::make('Added At', 'created_at')->sortable()->searchable(),
            Column::action('Action')
        ];
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
}
