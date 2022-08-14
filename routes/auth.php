<?php

use Illuminate\Support\Facades\Route;

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
        Route::get('/login', 'showLoginForm')
            ->name('login');
        Route::post('/login', 'login');
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

Route::resource('tokens', \App\Http\Controllers\Auth\TokenController::class)
    ->only(['create', 'store', 'destroy']);
