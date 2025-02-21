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
