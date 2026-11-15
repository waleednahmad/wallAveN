<?php

use App\Http\Controllers\Dashboard\RepresentativeController;
use Illuminate\Support\Facades\Route;



// =============== Guest Routes =================
Route::controller(RepresentativeController::class)
    ->middleware('guest:representative')
    ->name('representative.')
    ->group(
        function () {
            Route::get('/register', 'register')->name('register');
            // Bot guard (honeypot + timing) and at most 5 attempts a minute per visitor
            Route::post('submit-register', 'submitRegister')->name('submitRegister')
                ->middleware([\App\Http\Middleware\BlockFormBots::class, 'throttle:5,1']);
        }
    );


// =============== Authenticated Routes =================
Route::controller(RepresentativeController::class)
    ->middleware('auth:representative')
    ->name('representative.')
    ->group(
        function () {
            Route::get('dashboard', 'dashboard')->name('dashboard');

            Route::get('logout', 'logout')->name('logout');
        }
    );
