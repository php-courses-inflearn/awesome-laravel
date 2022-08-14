<?php

use Illuminate\Support\Facades\Route;

Route::controller(\App\Http\Controllers\Dashboard\UserController::class)->group(function () {
    Route::get('/', 'dashboard')
        ->name('dashboard');
});

Route::controller(\App\Http\Controllers\Dashboard\BlogController::class)->group(function () {
    Route::get('/blogs', 'dashboard')
        ->name('dashboard.blogs');
});

Route::controller(\App\Http\Controllers\Dashboard\SubscribeController::class)->group(function () {
    Route::get('/subscribers', 'subscribers')
        ->name('dashboard.subscribers');
    Route::get('/subscriptions', 'subscriptions')
        ->name('dashboard.subscriptions');
});

Route::controller(\App\Http\Controllers\Dashboard\CommentController::class)->group(function () {
    Route::get('/comments', 'dashboard')
        ->name('dashboard.comments');
});

Route::controller(\App\Http\Controllers\Dashboard\TokenController::class)->group(function () {
    Route::get('/tokens', 'dashboard')
        ->name('dashboard.tokens');
});
