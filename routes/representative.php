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
            Route::post('submit-register', 'submitRegister')->name('submitRegister');
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
