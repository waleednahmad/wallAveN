<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartTemp extends Model
{
    protected $guarded = [];

    // =======================
    // RELATIONS
    // =======================
    public function dealer()
    {
        return $this->belongsTo(Dealer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
