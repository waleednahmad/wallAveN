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

/**
 * URL of the small (600px) copy of an image when it exists, else the image itself.
 * Use where images are shown small: product cards, cart lines, thumbnail strips.
 * Thumbnails are written on upload and by `php artisan images:optimize` for older images.
 */
function thumb(?string $path): string
{
    if (!$path) {
        return '';
    }
    if (preg_match('#^(https?:)?//#', $path) || str_ends_with(strtolower($path), '.svg')) {
        return asset($path);
    }

    static $seen = [];
    $thumb = \App\Support\ImageOptimizer::thumbPathFor(ltrim($path, '/'));
    if (!array_key_exists($thumb, $seen)) {
        $seen[$thumb] = is_file(public_path($thumb));
    }

    return asset($seen[$thumb] ? $thumb : $path);
}
