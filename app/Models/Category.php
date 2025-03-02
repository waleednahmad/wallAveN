<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];

    // ===========================
    // RELATIONS
    // ===========================
    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'category_product',);
    }

    // ===========================
    // SCOPES
    // ===========================
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
