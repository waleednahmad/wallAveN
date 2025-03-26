<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Page;
use App\Models\PublicSetting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        $publicActiveCategories = Category::active()
            ->whereHas('products', function ($query) {
                $query->where('status', 1)->whereHas('variants');
            })
            ->orderBy('name')
            ->get();

        $footerContent =  Page::where('title', 'Footer')->first()->content ?? '';


        View::share('publicActiveCategories', $publicActiveCategories);
        View::share('footerContent', $footerContent);
    }
}
