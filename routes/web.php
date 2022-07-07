<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::domain('homestead.test')->group(function () {
    Route::get('/', \App\Http\Controllers\WelcomeController::class)
        ->name('home');
});

Route::domain('account.homestead.test')->group(function () {
    Route::controller(\App\Http\Controllers\Auth\RegisterController::class)
        ->middleware('guest')
        ->group(function () {
            Route::get('/register', 'showRegistrationForm')
                ->name('register');
            Route::post('/register', 'register');
        });
    Route::controller(\App\Http\Controllers\Auth\LoginController::class)->group(function () {
        Route::middleware('guest')->group(function () {
            Route::get('/', 'showLoginForm')
                ->name('login');
            Route::post('/', 'login');
        });
        Route::post('/logout', 'logout')
            ->name('logout')
            ->middleware('auth');
    });
    Route::controller(\App\Http\Controllers\Auth\EmailVerificationController::class)->group(function () {
        Route::name('verification.')
            ->prefix('/email')
            ->middleware('auth')
            ->group(function () {
                Route::get('/verify', 'notice')
                    ->name('notice');
                Route::get('/verify/{id}/{hash}', 'verify')
                    ->middleware('signed')
                    ->name('verify');
                Route::post('/verification-notification', 'send')
                    ->name('send');
            });
    });
});
