<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    protected $guarded = [];

    // =============================
    // ========= RELATIONS =========
    // =============================
    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_type_product',);
    }
}
