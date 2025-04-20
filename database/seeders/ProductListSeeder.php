<?php

namespace Database\Seeders;

use App\Models\PriceList;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defalutPriceList = [
            'name' => 'Default Price List',
            'percentage' => 1,
            'is_default' => true,
        ];
        if (!PriceList::where('is_default', true)->exists()) {
            PriceList::create($defalutPriceList);
        }
    }
}
