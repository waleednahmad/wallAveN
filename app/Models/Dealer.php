<?php

namespace App\Models;

use App\Observers\DealerObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;

#[ObservedBy([DealerObserver::class])]
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

    public function bannerSetting()
    {
        return $this->hasOne(DealerBannerSetting::class);
    }


    // ==================
    // Scopes
    // ==================
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
