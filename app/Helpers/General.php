<?php

use App\Models\AppLanguage;
use App\Models\PublicSetting;
use Illuminate\Support\Facades\Config;

/**
 * All store settings, loaded once per request (pages read them many times).
 * PublicSetting clears the cache when a setting is saved.
 */
function publicSettings(): array
{
    // Queue workers live for hours: read fresh there so a changed setting is picked up
    if (app()->runningInConsole()) {
        return PublicSetting::pluck('value', 'key')->all();
    }

    return app()->bound('gr.public_settings')
        ? app('gr.public_settings')
        : tap(PublicSetting::pluck('value', 'key')->all(), fn ($all) => app()->instance('gr.public_settings', $all));
}

function publicSetting(string $key, $default = null)
{
    return publicSettings()[$key] ?? $default;
}

/**
 * Who sees the catalog (setting "toggle show category & shop pages"):
 *  'closed'      0 = only signed-in dealers, reps and admins
 *  'open'        1 = everyone sees products; trade prices only after signing in
 *  'open-prices' 2 = everyone sees products and prices; ordering still needs an account
 */
function catalogMode(): string
{
    return match ((string) publicSetting('toggle show category & shop pages', '0')) {
        '1' => 'open',
        '2' => 'open-prices',
        default => 'closed',
    };
}

/* Company contact details (store settings "company phone", "company email", ...) */
function companyPhone(): string
{
    return (string) publicSetting('company phone', '');
}

/** "tel:" target for the phone: digits only, +1 added to 10-digit US numbers. */
function companyTel(): string
{
    $digits = preg_replace('/\D+/', '', companyPhone());
    return $digits === '' ? '' : '+' . (strlen($digits) === 10 ? '1' . $digits : $digits);
}

function companyEmail(): string
{
    return (string) publicSetting('company email', '');
}

function companyAddress(): string
{
    return (string) publicSetting('company address', '');
}

function companyCity(): string
{
    return (string) publicSetting('company city', '');
}

/** ['Mon – Sat' => '9:00 am – 7:30 pm', 'Sunday' => ...] without empty days */
function companyHours(): array
{
    return array_filter([
        'Mon – Sat' => (string) publicSetting('opening hours mon-sat', ''),
        'Sunday' => (string) publicSetting('opening hours sunday', ''),
    ], fn ($v) => trim($v) !== '');
}

/** Signed in as a dealer, sales rep or admin (the people who can order). */
function isTradeUser(): bool
{
    return auth('dealer')->check() || auth('representative')->check() || auth('web')->check();
}

/** Prices are shown to this visitor. */
function canSeePrices(): bool
{
    return isTradeUser() || catalogMode() === 'open-prices';
}

function getMinimumnItemsCount()
{
    return publicSetting('minimum items', 0);
}

function getMinimumPrice()
{
    return publicSetting('minimum price', 0);
}

function isVendorActivated()
{
    return publicSetting('activate vendor', 0);
}

function showBecomeARepInMenu()
{
    return publicSetting('show become a rep in the menu', 0);
}

function showCategoryAndShopPages()
{
    // Kept for older callers: true when guests may browse the catalog (with or without prices)
    return catalogMode() !== 'closed';
}

function isLargeMenuActivated()
{
    return publicSetting('activate-large-menu', 0);
}
function getMainImage()
{
    $value = publicSetting('main logo');
    if ($value !== null) {
        return asset($value);
    } else {
        return asset('assets/img/logo.webp');
    }
}

function getFavicon()
{
    $value = publicSetting('favicon');
    if ($value !== null) {
        return asset($value);
    } else {
        return asset('assets/img/favicon.png');
    }
}

function getGoogleAnalytics()
{
    return publicSetting('google analytics', '');
}


function getWebsiteTitle()
{
    return publicSetting('website title', '');
}

function getMinimumDealerSalePercentage()
{
    return publicSetting('minimum-dealer-sale-percentage', 1);
}

/**
 * URL of a small copy of an image when it exists, else the image itself.
 * $size 600: product cards, thumbnail strips. $size 240: colour swatches, quick add thumbnail strip.
 * Thumbnails are written on upload and by `php artisan images:optimize` for older images.
 */
function thumb(?string $path, int $size = 600): string
{
    if (!$path) {
        return '';
    }
    if (preg_match('#^(https?:)?//#', $path) || str_ends_with(strtolower($path), '.svg')) {
        return asset($path);
    }

    static $seen = [];
    $thumb = \App\Support\ImageOptimizer::thumbPathFor(ltrim($path, '/'), $size);
    if (!array_key_exists($thumb, $seen)) {
        $seen[$thumb] = is_file(public_path($thumb));
    }

    return asset($seen[$thumb] ? $thumb : $path);
}
