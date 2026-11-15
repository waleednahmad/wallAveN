<?php

use App\Http\Controllers\DealerController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\CheckIsDealerLoggedIn;
use App\Mail\TestEmail;
use App\Models\Dealer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Representative;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;



// Search engines: built from the store settings (domain, catalog visibility)
Route::get('/robots.txt', [\App\Http\Controllers\SeoController::class, 'robots'])->name('robots');
Route::get('/sitemap.xml', [\App\Http\Controllers\SeoController::class, 'sitemap'])->name('sitemap');

Route::controller(FrontController::class)->group(function () {
    Route::get('/', 'index')->name('frontend.home');
    Route::get('/product/{slug}', 'showProduct')->name('frontend.product');
    Route::get('/shop', 'shop')->name('frontend.shop');
    Route::get('/about-us', 'aboutUs')->name('frontend.aboutUs');
    Route::get('/contact-us', 'contactUs')->name('frontend.contactUs');
    // Route::get('/send-email', 'sendEmail'); // removed: public test endpoint
});

Route::controller(FrontController::class)
    ->middleware('guest:web')
    ->group(
        function () {

            Route::middleware([
                CheckIsDealerLoggedIn::class
            ])->group(function () {
                Route::get('register', 'register')->name('frontend.register');
                // Bot guard (honeypot + timing) and at most 5 attempts a minute per visitor
                Route::post('submit-register', 'submitRegister')->name('frontend.submitRegister')
                    ->middleware([\App\Http\Middleware\BlockFormBots::class, 'throttle:5,1']);

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
        Route::get('orders/{order}/print', 'print')->name('orders.print');
    });


Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect()->route('frontend.home')
        ->with('success', 'Email verified successfully.');
})->middleware(['auth:web,dealer,representative', 'signed'])
    // Renamed: routes/auth.php registers 'verification.verify' later and that one is used; one name per route
    // keeps `php artisan route:cache` working
    ->name('verification.verify.multi-guard');

// preview-email test route removed

// Route::get('test', function () {
//     echo phpinfo();
// });
require __DIR__ . '/auth.php';
