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

final class RepresentativeDealersAbandonedCartsTable extends PowerGridComponent
{
    public int $representativeId;
    public string $tableName = 'representative-dealers-abandoned-carts-table';

    // Newest cart first by default (last item added); clicking a column header sorts by that column instead
    public string $sortField = 'last_cart_temp_date';
    public string $sortDirection = 'desc';

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
            $query->whereNotNull('representative_id')
                ->whereNull('admin_id');
        })
            ->whereHas('cartTemps', function ($query) {
                $query->where('representative_id', $this->representativeId)
                    ->whereNull('admin_id');
            })
            ->join('cart_temps', function ($join) {
                $join->on('dealers.id', '=', 'cart_temps.dealer_id')
                    ->where('cart_temps.representative_id', $this->representativeId)
                    ->whereNull('cart_temps.admin_id');
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
            ->add('dealer_cell', function ($row) {
                $sub = array_map(fn ($v) => '<span class="adm-nowrap">' . e($v) . '</span>', array_filter([$row->email, $row->phone]));
                return '<div class="adm-cell-main">' . e($row->company_name ?: ($row->name ?: '-')) . '</div>'
                    . ($sub ? '<div class="adm-cell-sub">' . implode(' · ', $sub) . '</div>' : '');
            })
            ->add('total_cart_amount', function ($row) {
                return "$" . number_format($row->total_cart_amount, 2);
            })
            ->add('created_at')
            ->add('last_activity', fn ($row) => $row->last_cart_temp_date ? \Illuminate\Support\Carbon::parse($row->last_cart_temp_date)->format('M j, Y · g:i A') : '');
    }

    public function columns(): array
    {
        return [
            Column::make('Dealer', 'dealer_cell', 'dealers.company_name')->bodyAttribute('gr-wrap')
                ->visibleInExport(false),
            Column::make('Name', 'company_name', 'dealers.company_name')->searchable()->hidden(),
            Column::make('Email', 'email', 'dealers.email')->searchable()->hidden(),
            Column::make('Phone', 'phone', 'dealers.phone')->searchable()->hidden(),
            Column::make('Items', 'total_cart_quantity')->headerAttribute('text-end')->bodyAttribute('text-end')->sortUsing(fn ($query, $dir) => $query->orderBy('total_cart_quantity', $dir)),
            Column::make('Total', 'total_cart_amount')->headerAttribute('text-end')->bodyAttribute('gr-nowrap text-end')->sortUsing(fn ($query, $dir) => $query->orderBy('total_cart_amount', $dir)),
            Column::make('Last item added', 'last_activity', 'last_cart_temp_date')->bodyAttribute('gr-nowrap')->sortUsing(fn ($query, $dir) => $query->orderBy('last_cart_temp_date', $dir)),
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
