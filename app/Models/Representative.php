<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Representative extends Authenticatable implements MustVerifyEmail
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

    public function referredDealers($status)
    {
        if ($status == 'approved') {
            return $this->hasMany(Dealer::class, 'referal_id')
                ->active()
                ->where('is_approved', 1);
        }
        return $this->hasMany(Dealer::class, 'referal_id');
    }

    public function cartTemps()
    {
        return $this->hasMany(CartTemp::class);
    }

    public function buyingFor()
    {
        return $this->belongsTo(Dealer::class, 'buying_for_id');
    }


    // ==================
    // Scopes
    // ==================
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
