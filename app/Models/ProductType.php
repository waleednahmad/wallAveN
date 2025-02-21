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
}
