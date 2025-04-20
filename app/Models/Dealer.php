<?php

namespace App\Models;

use App\Observers\DealerObserver;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[ObservedBy([DealerObserver::class])]
class Dealer extends Authenticatable implements MustVerifyEmail
{

    use Notifiable;

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

    public function referalTo()
    {
        return $this->belongsTo(Representative::class, 'referal_id');
    }


    public function priceList()
    {
        return $this->hasOne(PriceList::class, 'id', 'price_list_id')
                    ->selectRaw('id, percentage / 100 as percentage');
    }

    // ==================
    // Scopes
    // ==================
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
