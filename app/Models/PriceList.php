<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceList extends Model
{
    protected $fillable = ['name', 'percentage', 'is_default'];

    public function dealers()
    {
        return $this->hasMany(Dealer::class);
    }
}
