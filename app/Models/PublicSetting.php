<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublicSetting extends Model
{
    protected $guarded = [];

    protected static function booted(): void
    {
        // Settings are cached for the request by publicSettings(); drop that copy when one changes
        static::saved(fn () => app()->forgetInstance('gr.public_settings'));
    }
}
