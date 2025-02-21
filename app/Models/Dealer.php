<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Dealer extends Authenticatable
{
    protected $guarded = [];

    // ==================
    // Relationships
    // ==================
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function cartTemps()
    {
        return $this->hasMany(CartTemp::class);
    }


    // ==================
    // Scopes
    // ==================
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
