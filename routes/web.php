<?php

use App\Http\Controllers\DealerController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\CheckIsDealerLoggedIn;
use App\Mail\TestEmail;
use App\Models\Product;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;



Route::controller(FrontController::class)->group(function () {
    Route::get('/', 'index')->name('frontend.home');
    Route::get('/product/{slug}', 'showProduct')->name('frontend.product');
    Route::get('/shop', 'shop')->name('frontend.shop');
    Route::get('/about-us', 'aboutUs')->name('frontend.aboutUs');
    Route::get('/contact-us', 'contactUs')->name('frontend.contactUs');
    Route::get('/send-email', 'sendEmail');
});

Route::controller(FrontController::class)
    ->middleware('guest:web')
    ->group(
        function () {

            Route::middleware([
                CheckIsDealerLoggedIn::class
            ])->group(function () {
                Route::get('register', 'register')->name('frontend.register');
                Route::post('submit-register', 'submitRegister')->name('frontend.submitRegister');

                Route::get('login', 'login')->name('login');
                Route::post('submit-login', 'submitLogin')->name('frontend.submitLogin');
            });

            Route::middleware('auth:dealer')->group(function () {
                Route::get('logout', 'logout')->name('frontend.logout');
            });
        }
    );

// Dealer Routes :
// -----------------------------------
Route::controller(DealerController::class)
    ->prefix('dealer')
    ->name('dealer.')
    ->middleware('auth:dealer')
    ->group(function () {
        Route::get('dashboard', 'dashboard')->name('dashboard');
        Route::get('profile', 'profile')->name('profile');
        Route::get('orders', 'orders')->name('orders');
        Route::get('customer-mode', 'customerMode')->name('customerMode');
    });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

// require __DIR__ . '/auth.php';
