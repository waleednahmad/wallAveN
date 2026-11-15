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

        // Storefront header/footer data: only loaded when those views render (not on admin pages,
        // Livewire updates or artisan commands), and cached for 10 minutes.
        View::composer(['frontend.layout.partials.header', 'frontend.layout.partials.footer'], function ($view) {
            $view->with('publicActiveCategories', Cache::remember('public_active_categories', 600, function () {
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
            }));
            $view->with('footerContent', Cache::remember('footer_content', 600, function () {
                return Page::where('title', 'Footer')->first()->content ?? '';
            }));
        });
    }
}
