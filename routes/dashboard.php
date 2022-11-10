<?php

use Illuminate\Support\Facades\Route;

Route::controller(\App\Http\Controllers\Dashboard\UserController::class)->group(function () {
    Route::get('/', 'index')
        ->name('dashboard');
});

Route::controller(\App\Http\Controllers\Dashboard\BlogController::class)->group(function () {
    Route::get('/blogs', 'index')
        ->name('dashboard.blogs');
});

Route::controller(\App\Http\Controllers\Dashboard\SubscriberController::class)->group(function () {
    Route::get('/subscribers', 'index')
        ->name('dashboard.subscribers');
});
Route::controller(\App\Http\Controllers\Dashboard\SubscriptionController::class)->group(function () {
    Route::get('/subscriptions', 'index')
        ->name('dashboard.subscriptions');
});

Route::controller(\App\Http\Controllers\Dashboard\CommentController::class)->group(function () {
    Route::get('/comments', 'index')
        ->name('dashboard.comments');
});

Route::controller(\App\Http\Controllers\Dashboard\TokenController::class)->group(function () {
    Route::get('/tokens', 'index')
        ->name('dashboard.tokens');
});
