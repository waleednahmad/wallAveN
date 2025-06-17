<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    // ===========================
    // RELATIONS
    // ===========================
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function subCategories()
    {
        return $this->belongsToMany(SubCategory::class, 'sub_category_product');
    }

    public function productTypes()
    {
        return $this->belongsToMany(ProductType::class, 'product_type_product');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function firstVariant()
    {
        return $this->hasOne(ProductVariant::class)->oldest();
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }


    // ===========================
    // SCOPES
    // ===========================  
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}



// $product = Product::with('variants.attributeValues.attribute')->findOrFail($productId);

// $groupedAttributes = [];

// foreach ($product->variants as $variant) {
//     foreach ($variant->attributeValues as $attributeValue) {
//         $attributeName = $attributeValue->attribute->name;
//         $attributeValueValue = $attributeValue->value;

//         if (!isset($groupedAttributes[$attributeName])) {
//             $groupedAttributes[$attributeName] = [];
//         }

//         if (!in_array($attributeValueValue, $groupedAttributes[$attributeName])) {
//             $groupedAttributes[$attributeName][] = $attributeValueValue;
//         }
//     }
// }
