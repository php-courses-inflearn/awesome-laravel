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

Route::get('/', \App\Http\Controllers\WelcomeController::class);

Route::get('/search', \App\Http\Controllers\SearchController::class)
    ->name('search');

Route::resource('blogs', \App\Http\Controllers\BlogController::class);

Route::controller(\App\Http\Controllers\SubscribeController::class)->group(function () {
    Route::post('subscribe', 'store')
        ->name('subscribe');
    Route::post('unsubscribe', 'destroy')
        ->name('unsubscribe');
});

Route::resource('blogs.posts', \App\Http\Controllers\PostController::class)
    ->shallow();

Route::resource('posts.comments', \App\Http\Controllers\CommentController::class)
    ->shallow()
    ->only(['store', 'update', 'destroy']);

Route::resource('posts.attachments', \App\Http\Controllers\AttachmentController::class)
    ->shallow()
    ->only(['store', 'destroy']);
