<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Page;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        $publicActiveCategories = Cache::remember('public_active_categories', 600, function () {
            return Category::active()
                ->where(function ($q) {
                    $q->whereHas('subcategories', function ($subQuery) {
                        $subQuery->whereHas('products', function ($query) {
                            $query->where('status', 1)->whereHas('variants');
                        });
                    })->orWhereHas('products', function ($query) {
                        $query->where('status', 1)->whereHas('variants');
                    });
                })
                ->orderBy('name')
                ->get();
        });

        $footerContent = Cache::remember('footer_content', 600, function () {
            return Page::where('title', 'Footer')->first()->content ?? '';
        });

        View::share('publicActiveCategories', $publicActiveCategories);
        View::share('footerContent', $footerContent);
    }
}
