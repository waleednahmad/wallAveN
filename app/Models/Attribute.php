<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute as AttrEl;

class Attribute extends Model
{
    protected $guarded = [];

    // ===========================
    // Relationships
    // ===========================
    public function values()
    {
        return $this->hasMany(AttributeValue::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }




    // =========== Accessors ==============
    protected function name(): AttrEl
    {
        return AttrEl::make(
            get: fn(string $value) => ucwords(strtolower($value))
        );
    }
}
