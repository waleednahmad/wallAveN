<?php

use App\Livewire\Frontend\ShopPage;
use App\Models\Product;
use App\Models\ProductVariant;
use Livewire\Livewire;

function createShopPageProduct(string $name, int $daysAgo): Product
{
    $createdAt = now()->subDays($daysAgo);

    $product = Product::create([
        'name' => $name,
        'sku' => $name,
        'status' => 1,
        'created_at' => $createdAt,
        'updated_at' => $createdAt,
    ]);

    ProductVariant::create([
        'product_id' => $product->id,
        'sku' => $name,
    ]);

    return $product;
}

it('shows products added in the last 60 days in new arrivals', function () {
    createShopPageProduct('Recent product', 1);
    createShopPageProduct('Older product', 61);

    Livewire::withQueryParams(['new_arrivals' => '1'])
        ->test(ShopPage::class)
        ->assertSet('newArrivals', true)
        ->assertViewHas('products', function ($products) {
            return $products->total() === 1
                && $products->getCollection()->first()->name === 'Recent product';
        });
});

it('shows only the 30 latest products when there are no recent arrivals', function () {
    foreach (range(61, 91) as $daysAgo) {
        createShopPageProduct("Product {$daysAgo} days ago", $daysAgo);
    }

    Livewire::withQueryParams(['new_arrivals' => '1'])
        ->test(ShopPage::class)
        ->assertViewHas('products', function ($products) {
            return $products->total() === 30
                && $products->getCollection()->first()->name === 'Product 61 days ago';
        });
});
