<?php

namespace App\Providers;

use App\Models\Category;
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

        View::composer([
            'frontend.layout.app'
        ], function ($view) {
            // ----- Active Categories -----
            $view->with('publicActiveCategories', Category::active()
                ->whereHas('products', function ($query) {
                    $query->where('status', 1)->whereHas('variants');
                })
                ->orderBy('name')
                ->get());
        });
    }
}
