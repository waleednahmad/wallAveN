<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Product extends Model
{
    protected $table = 'products';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'published' => 'boolean',
        ];
    }

    // ===================== Accessories =====================
    // -- SKU
    protected function sku(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $attributes['SKU'],
        );
    }

    // -- Handle
    protected function handle(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $attributes['Handle'],
        );
    }

    // -- Title
    protected function title(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $attributes['Title'],
        );
    }

    // -- Body (HTML)
    protected function bodyHtml(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $attributes['Body (HTML)'],
        );
    }

    // -- Vendor
    protected function vendor(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $attributes['Vendor'],
        );
    }

    // -- Type
    // protected function type(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn($value, $attributes) => $attributes['Type'] ,
    //     );
    // }

    // -- Tags
    protected function tags(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $attributes['tags'],
        );
    }

    // -- Published
    protected function published(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $attributes['Published'],
        );
    }

    // -- Option1 Name
    protected function option1Name(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $attributes['Option1 Name'],
        );
    }

    // -- Option1 Value
    protected function option1Value(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $attributes['Option1 Value'],
        );
    }

    // -- Option1 Linked To
    protected function option1LinkedTo(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $attributes['Option1 Linked To'],
        );
    }

    // -- Option2 Name
    protected function option2Name(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $attributes['Option2 Name'],
        );
    }

    // -- Option2 Value
    protected function option2Value(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $attributes['Option2 Value'],
        );
    }

    // -- Option2 Linked To
    protected function option2LinkedTo(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $attributes['Option2 Linked To'],
        );
    }

    // -- Option3 Name
    protected function option3Name(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $attributes['Option3 Name'],
        );
    }

    // -- Option3 Value
    protected function option3Value(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $attributes['Option3 Value'],
        );
    }

    // -- Option3 Linked To
    protected function option3LinkedTo(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $attributes['Option3 Linked To'],
        );
    }

    // -- Variant SKU
    protected function variantSku(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $attributes['Variant SKU'],
        );
    }

    // -- Variant Grams
    protected function variantGrams(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $attributes['Variant Grams'],
        );
    }

    // -- Variant Inventory Tracker
    protected function variantInventoryTracker(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $attributes['Variant Inventory Tracker'],
        );
    }

    // -- Variant Inventory Qty
    protected function variantInventoryQty(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $attributes['Variant Inventory Qty'],
        );
    }

    // -- Variant Inventory Policy
    protected function variantInventoryPolicy(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $attributes['Variant Inventory Policy'],
        );
    }

    // -- Variant Fulfillment Service
    protected function variantFulfillmentService(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $attributes['Variant Fulfillment Service'],
        );
    }

    // -- Variant Price
    protected function variantPrice(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $attributes['Variant Price'],
        );
    }

    // -- Variant Compare At Price
    protected function variantCompareAtPrice(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $attributes['Variant Compare At Price'],
        );
    }

    // -- Variant Requires Shipping
    protected function variantRequiresShipping(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $attributes['Variant Requires Shipping'],
        );
    }

    // -- Variant Taxable
    protected function variantTaxable(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $attributes['Variant Taxable'],
        );
    }

    // -- Variant Barcode
    protected function variantBarcode(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $attributes['Variant Barcode'],
        );
    }

    // -- Image Src
    protected function imageSrc(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $attributes['Image Src'],
        );
    }

    // -- Image Position
    protected function imagePosition(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $attributes['Image Position'],
        );
    }

    // -- Image Alt Text
    protected function imageAltText(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $attributes['Image Alt Text'],
        );
    }

    // -- Gift Card
    protected function giftCard(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $attributes['Gift Card'],
        );
    }

    // -- SEO Title
    protected function seoTitle(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $attributes['SEO Title'],
        );
    }

    // -- SEO Description
    protected function seoDescription(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $attributes['SEO Description'],
        );
    }

    // Variant Image
    protected function variantImage(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $attributes['Variant Image'],
        );
    }

    // Variant Weight Unit
    protected function variantWeightUnit(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $attributes['Variant Weight Unit'],
        );
    }

    // Variant Tax Code
    protected function variantTaxCode(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $attributes['Variant Tax Code'],
        );
    }

    // Cost per item
    protected function costPerItem(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $attributes['Cost per item'],
        );
    }

    // Status
    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $attributes['Status'],
        );
    }
}
