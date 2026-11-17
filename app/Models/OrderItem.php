<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id')->withTrashed();
    }

    /**
     * Photo to show for this line (invoice, emails): the one saved with the order, else the variant's,
     * else the product's main photo. Variants without their own photo used to save an empty image.
     * Returns a path relative to public/, or null.
     */
    public function displayImage(): ?string
    {
        foreach ([$this->image, $this->variant?->image, $this->product?->image] as $path) {
            if ($path && is_file(public_path(ltrim($path, '/')))) {
                return $path;
            }
        }

        return null;
    }
}
