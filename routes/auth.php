<?php

use Illuminate\Support\Facades\Route;

Route::controller(\App\Http\Controllers\Auth\RegisterController::class)->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/register', 'create')
            ->name('register');
        Route::post('/register', 'store');
    });
});

Route::controller(\App\Http\Controllers\Auth\EmailVerificationController::class)->group(function () {
    Route::name('verification.')->prefix('/email')->group(function () {
        Route::middleware('auth')->group(function () {
            Route::get('/verify', 'create')
                ->name('notice');
            Route::get('/verify/{id}/{hash}', 'update')
                ->name('verify')
                ->middleware('signed');
            Route::post('/verification-notification', 'store')
                ->name('send');
        });
    });
});

Route::controller(\App\Http\Controllers\Auth\LoginController::class)->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', 'create')
            ->name('login');
        Route::post('/login', 'store');
    });
    Route::post('/logout', 'destroy')
        ->name('logout')
        ->middleware('auth');
});

Route::controller(\App\Http\Controllers\Auth\SocialLoginController::class)->group(function () {
    Route::middleware('guest')->name('login.')->group(function () {
        Route::get('/login/{provider}', 'create')
            ->name('social');
        Route::get('/login/{provider}/callback', 'store')
            ->name('social.callback');
    });
});

Route::controller(\App\Http\Controllers\Auth\PasswordResetController::class)->group(function () {
    Route::middleware('guest')->name('password.')->group(function () {
        Route::get('/forgot-password', 'create')
            ->name('request');
        Route::post('/forgot-password', 'store')
            ->name('email');
        Route::get('/reset-password/{token}', 'edit')
            ->name('reset');
        Route::post('/reset-password', 'update')
            ->name('update');
    });
});

Route::controller(\App\Http\Controllers\Auth\PasswordConfirmController::class)->group(function () {
    Route::middleware('auth')->group(function () {
        Route::get('/confirm-password', 'create')
            ->name('password.confirm');
        Route::post('/confirm-password', 'store');
    });
});

Route::singleton('profile', \App\Http\Controllers\Auth\ProfileController::class)
    ->middleware('password.confirm');

Route::resource('tokens', \App\Http\Controllers\Auth\TokenController::class)
    ->only(['create', 'store', 'destroy']);

Route::withoutMiddleware('web')->middleware('api')->group(function () {
    Route::controller(\App\Http\Controllers\Auth\JwtLoginController::class)->group(function () {
        Route::name('jwt.')->prefix('jwt')->group(function () {
            Route::post('login', 'store')
                ->name('login');
            Route::middleware('auth:api')->group(function () {
                Route::put('refresh', 'update')
                    ->name('refresh');
                Route::delete('logout', 'destroy')
                    ->name('logout');
            });
        });
    });
});
