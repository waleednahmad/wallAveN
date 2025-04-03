<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Casts\Attribute as AttrEl;

class AttributeValue extends Model
{
    protected $guarded = [];

    // ===========================
    // RELATIONS
    // ===========================
    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function productVariants()
    {
        return $this->belongsToMany(ProductVariant::class, 'product_variant_attribute_values');
    }


    // =========== Accessors ==============
    protected function value(): AttrEl
    {
        return AttrEl::make(
            get: fn(string $value) => ucwords(strtolower($value)),
        );
    }
}
