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

function showBecomeARepInMenu()
{
    return PublicSetting::where('key', 'show become a rep in the menu')->first()->value ?? 0;
}

function showCategoryAndShopPages()
{
    return PublicSetting::where('key', 'toggle show category & shop pages')->first()->value ?? 0;
}

function isLargeMenuActivated()
{
    return PublicSetting::where('key', 'activate-large-menu')->first()->value ?? 0;
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

function getFavicon()
{
    $value = PublicSetting::where('key', 'favicon')->first();
    if ($value) {
        return asset($value->value);
    } else {
        return asset('assets/img/favicon.png');
    }
}

function getGoogleAnalytics()
{
    return PublicSetting::where('key', 'google analytics')->first()->value ?? '';
}


function getWebsiteTitle()
{
    return PublicSetting::where('key', 'website title')->first()->value ?? '';
}

function getMinimumDealerSalePercentage()
{
    return PublicSetting::where('key', 'minimum-dealer-sale-percentage')->first()->value ?? 1;
}
