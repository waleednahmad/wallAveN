<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $guarded = [];


    // ===========================
    // RELATIONS
    // ===========================
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function productTypes()
    {
        return $this->hasMany(ProductType::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'sub_category_product',);
    }
}
