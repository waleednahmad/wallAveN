<?php

use App\Http\Controllers\Dashboard\AdminController;
use App\Http\Controllers\Dashboard\AllTransactionController;
use App\Http\Controllers\Dashboard\AttributeController;
use App\Http\Controllers\Dashboard\CampaignController;
use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\ClientController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\DealerController;
use App\Http\Controllers\Dashboard\EmployeeController;
use App\Http\Controllers\Dashboard\InfluencerController;
use App\Http\Controllers\Dashboard\OrderController;
use App\Http\Controllers\Dashboard\PageBreadcrumpController;
use App\Http\Controllers\Dashboard\PaymentPolicyController;
use App\Http\Controllers\Dashboard\PriceListController;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Dashboard\ProductTypeController;
use App\Http\Controllers\Dashboard\ProjectController;
use App\Http\Controllers\Dashboard\PublicSettingController;
use App\Http\Controllers\Dashboard\RepresentativeController;
use App\Http\Controllers\Dashboard\SeoPageController;
use App\Http\Controllers\Dashboard\SubCategoryController;
use App\Http\Controllers\Dashboard\VendorController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
*/

Livewire::setUpdateRoute(function ($handle) {
    return Route::post('demo/public/livewire/update', $handle);
});

Route::prefix('super_admin')
    ->group(function () {

        // Dashboard Routes
        // ---------------------
        Route::controller(DashboardController::class)
            ->middleware('auth:web')
            ->group(function () {
                Route::get('/', 'index')->name('dashboard');
                Route::get('logout', 'logout')->name('logout');
            });

        // Dashboard Routes
        // ==============================================================================
        Route::name('dashboard.')
            ->middleware('auth:web')
            ->group(function () {
                // Admins Routes :
                // ------------------------------------------
                Route::resource('admins', AdminController::class)->only(['index']);

                // Vendors Routes :
                // ------------------------------------------
                Route::resource('vendors', VendorController::class)->only(['index']);

                // Products Routes :
                // ------------------------------------------
                Route::resource('products', ProductController::class)->only(['index', 'create', 'edit']);
                route::controller(ProductController::class)->group(function () {
                    Route::get('products/{id}/create-variant', 'createVariant')->name('products.create-variant');
                });

                // Dealers Routes :
                // ------------------------------------------
                Route::resource('dealers', DealerController::class)->only(['index']);



                //  Orders Routes :
                // ------------------------------------------
                Route::resource('orders', OrderController::class)->only(['index']);
                Route::get('orders/{order}/print', [OrderController::class, 'print'])->name('orders.print');
                Route::get('orders/{order}/pdf', [OrderController::class, 'pdf'])
                    ->name('orders.pdf');

                // Public Settings Routes :
                // ------------------------------------------
                Route::resource('public-settings', PublicSettingController::class)->only(['index']);

                // Representaives Routes :
                // ------------------------------------------
                Route::resource('representatives', RepresentativeController::class)->only(['index']);

                // Categories Routes :
                // ------------------------------------------
                Route::resource('categories', CategoryController::class)->only(['index']);

                // Sub Categories Routes :
                // ------------------------------------------
                Route::resource('sub-categories', SubCategoryController::class)->only(['index']);

                // Product Type Routes :
                // ------------------------------------------
                Route::resource('product-types', ProductTypeController::class)->only(['index']);

                // Attributes Routes :
                // ---------------------
                Route::resource('attributes', AttributeController::class)->only(['index']);

                // Page Breadcrumps Routes :
                // ---------------------
                Route::resource('page-breadcrumps', PageBreadcrumpController::class)->only(['index']);


                // Price List Routes :
                // ---------------------
                Route::resource('price-lists', PriceListController::class)->only(['index']);

                // SEO Pages Routes :
                // ---------------------
                Route::resource('seo-pages', SeoPageController::class)->only(['index']);
            });
    });


// Profile Routes
// by : Ahmad Alsakhen
// ---------------------
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
