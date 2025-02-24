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

    // ===========================
    // SCOPES
    // ===========================
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
