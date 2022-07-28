<?php

namespace App\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class HttpClientServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Http::macro('api', function ($token) {
            $baseUrl = env('APP_API_URL');

            return Http::withToken($token)
                ->withHeaders([
                    'Content-Type' => 'application/json'
                ])
                ->baseUrl($baseUrl);
        });
    }
}
