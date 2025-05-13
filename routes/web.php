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
        Route::get('orders/{order}/print', 'print')->name('orders.print');
    });


Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect()->route('frontend.home')
        ->with('success', 'Email verified successfully.');
})->middleware(['auth:web,dealer,representative', 'signed'])->name('verification.verify');

Route::get('preview-email', function () {
    $order = Order::first();
    $address = $order->dealer->address ? explode(',', $order->dealer->address)[0] : '---';
    $city = $order->dealer->city ?? '---';
    $state = $order->dealer->state ?? '---';
    $zip_code = $order->dealer->zip_code ?? '---';

    return view(
        'emails.contact-form',
        [
            'name' => $order->dealer->company_name,
            'email' => $order->dealer->email,
            'phone' => $order->dealer->phone,
            'subject' => 'Test Subject',
            'message' => 'Test Message',
        ]
    );
});

// Route::get('test', function () {
//     echo phpinfo();
// });
require __DIR__ . '/auth.php';
