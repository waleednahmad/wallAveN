<?php

use App\Models\AppLanguage;
use App\Models\PublicSetting;
use Illuminate\Support\Facades\Config;

function getMinimumnItemsCount()
{
    return PublicSetting::where('key', 'minimum items')->first()->value ?? 0;
}

function getMinimumPrice()
{
    return PublicSetting::where('key', 'minimum price')->first()->value ?? 0;
}

function isVendorActivated()
{
    return PublicSetting::where('key', 'activate vendor')->first()->value ?? 0;
}

function getMainImage()
{
    $value = PublicSetting::where('key', 'main logo')->first();
    if ($value) {
        return asset($value->value);
    } else {
        return asset('assets/img/logo.webp');
    }
}
