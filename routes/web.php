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
Route::get('/', \App\Http\Controllers\WelcomeController::class)
    ->name('home');

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

Route::prefix('/dashboard')->group(function () {
    Route::middleware(['auth', 'password.confirm'])->group(function () {
        Route::controller(\App\Http\Controllers\Dashboard\ProfileController::class)->group(function () {
            Route::get('/profile', 'dashboard')
                ->name('dashboard.profile');
            Route::put('/profile', 'update');
            Route::delete('/profile', 'destroy');
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
    });
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('blogs', \App\Http\Controllers\BlogController::class);
    Route::controller(\App\Http\Controllers\SubscribeController::class)->group(function () {
        Route::post('subscribe/{blog}', 'subscribe')
            ->name('subscribe');
        Route::delete('unsubscribe/{blog}', 'unsubscribe')
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
});
