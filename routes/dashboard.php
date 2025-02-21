<?php

use App\Http\Controllers\Dashboard\AdminController;
use App\Http\Controllers\Dashboard\AllTransactionController;
use App\Http\Controllers\Dashboard\CampaignController;
use App\Http\Controllers\Dashboard\ClientController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\DealerController;
use App\Http\Controllers\Dashboard\EmployeeController;
use App\Http\Controllers\Dashboard\InfluencerController;
use App\Http\Controllers\Dashboard\OrderController;
use App\Http\Controllers\Dashboard\PaymentPolicyController;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Dashboard\ProjectController;
use App\Http\Controllers\Dashboard\PublicSettingController;
use App\Http\Controllers\Dashboard\RepresentativeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
*/


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

        // Products Routes :
        // ------------------------------------------
        Route::resource('products', ProductController::class)->only(['index']);

        // Dealers Routes :
        // ------------------------------------------
        Route::resource('dealers', DealerController::class)->only(['index']);

        //  Orders Routes :
        // ------------------------------------------
        Route::resource('orders', OrderController::class)->only(['index']);

        // Public Settings Routes :
        // ------------------------------------------
        Route::resource('public-settings', PublicSettingController::class)->only(['index']);

        // Representaives Routes :
        // ------------------------------------------
        Route::resource('representatives', RepresentativeController::class)->only(['index']);
    });

// Profile Routes
// by : Ahmad Alsakhen
// ---------------------
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
