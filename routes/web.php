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
    Route::controller(\App\Http\Controllers\Auth\RegisterController::class)->group(function () {
        Route::middleware('guest')->group(function () {
            Route::get('/register', 'showRegistrationForm')
                ->name('register');
            Route::post('/register', 'register');
        });
    });
    Route::controller(\App\Http\Controllers\Auth\EmailVerificationController::class)->group(function () {
        Route::name('verification.')->prefix('/email')->group(function () {
            Route::middleware('auth')->group(function () {
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
    Route::controller(\App\Http\Controllers\Auth\GithubLoginController::class)->group(function () {
        Route::middleware('guest')->group(function () {
            Route::get('/login/github', 'redirect')
                ->name('login.github');
            Route::get('/login/github/callback', 'callback');
        });
    });
    Route::controller(\App\Http\Controllers\Auth\PasswordResetController::class)->group(function () {
        Route::middleware('guest')->group(function () {
            Route::get('/forgot-password', 'request')
                ->name('password.request');
            Route::post('/forgot-password', 'email')
                ->name('password.email');
            Route::get('/reset-password/{token}', 'reset')
                ->name('password.reset');
            Route::post('/reset-password', 'update')
                ->name('password.update');
        });
    });
    Route::controller(\App\Http\Controllers\Auth\PasswordConfirmController::class)->group(function () {
        Route::middleware('auth')->group(function () {
            Route::get('/confirm-password', 'showPasswordConfirmationForm')
                ->name('password.confirm');
            Route::post('/confirm-password', 'confirm');
        });
    });
    Route::prefix('/dashboard')->group(function () {
        Route::controller(\App\Http\Controllers\Dashboard\ProfileController::class)->group(function () {
            Route::middleware(['auth', 'password.confirm'])->group(function () {
                Route::get('/profile', 'show')
                    ->name('dashboard.profile');
                Route::put('/profile', 'update');
            });
        });
    });
});
