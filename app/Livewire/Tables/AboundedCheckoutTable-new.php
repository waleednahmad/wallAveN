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
        return Dealer::whereHas('cartTemps')
            ->join('cart_temps', function ($join) {
                $join->on('dealers.id', '=', 'cart_temps.dealer_id');
            })
            ->leftJoin('users as admins', 'cart_temps.admin_id', '=', 'admins.id')
            ->leftJoin('representatives', 'cart_temps.representative_id', '=', 'representatives.id')
            ->select([
                'dealers.*',
                DB::raw('SUM(cart_temps.total) as total_cart_amount'),
                DB::raw('SUM(cart_temps.quantity) as total_cart_quantity'),
                DB::raw('
                        CASE 
                            WHEN cart_temps.admin_id IS NOT NULL THEN "admin"
                            WHEN cart_temps.representative_id IS NOT NULL THEN "representative"
                            ELSE "dealer" 
                        END as added_by
                        '),
                DB::raw('
                        CASE 
                            WHEN cart_temps.admin_id IS NOT NULL THEN cart_temps.admin_id
                            WHEN cart_temps.representative_id IS NOT NULL THEN cart_temps.representative_id
                            ELSE dealers.id
                        END as added_by_id
                        '),
                DB::raw('admins.name as admin_name'),
                DB::raw('representatives.name as representative_name'),
                // i want to get a unique number foreach record
                DB::raw(

                    '
                        CASE 
                            WHEN cart_temps.admin_id IS NOT NULL THEN CONCAT("admin_", cart_temps.admin_id, "_dealer_", dealers.id)
                            WHEN cart_temps.representative_id IS NOT NULL THEN CONCAT("representative_", cart_temps.representative_id, "_dealer_", dealers.id)
                        END
                    as row_unique_id'
                ),
                'cart_temps.dealer_id as dealer_id',
                'cart_temps.admin_id',
                'cart_temps.representative_id'
            ])
            ->groupBy('dealers.id', 'added_by', 'cart_temps.admin_id', 'cart_temps.representative_id')
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
            ->add('total_cart_amount', function ($row) {
                return "$" . number_format($row->total_cart_amount, 2);
            })
            ->add('added_by_name', function ($row) {
                if ($row?->added_by === 'admin') {
                    return $row?->admin_name ?? $row?->company_name;
                } elseif ($row?->added_by === 'representative') {
                    return $row?->representative_name ?? $row?->company_name;
                }
                return $row?->company_name;
            });
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'row_unique_id'),

            Column::make('Name', 'company_name')
                ->searchable(),

            Column::make('Quantity', 'total_cart_quantity')
                ->sortable(),

            Column::make('Total', 'total_cart_amount')
                ->sortable(),

            Column::make('Added By', 'added_by'),
            Column::make('Added By', 'added_by_id'),

            Column::make('Added By Name', 'added_by_name'),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [];
    }

    // #[On('show')]
    // public function show($id, $addedBy = null): void
    // {
    //     $this->dispatch('previewAboundedCheckout', ['dealer' => $id, 'addedBy' => $addedBy]);
    // }

    public function actions($row): array
    {
        return [
            Button::add('show')
                ->slot('<i class="fas fa-eye"></i>')
                ->class('btn btn-primary btn-sm rounded')
                ->dispatch('previewAboundedCheckout', [
                    'dealer_id' => $row->row_unique_id ?? $row->id,
                    'addedBy' => $row->added_by ?? null,
                    'addedById' => $row->added_by_id ?? null
                ]), 
        ];
    }

    public function getRowNum($row): int
    {
        static $ids = null;

        if ($ids === null) {
            $ids = $this->datasource()->pluck('id')->values();
        }

        $index = $ids->search($row->id);

        return $index !== false ? $index + 1 : 0;
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
