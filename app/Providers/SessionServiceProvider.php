<?php

namespace App\Providers;

use App\Enums\Provider;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;

/**
 * @mixin \Illuminate\Contracts\Session\Session
 */
class SessionServiceProvider extends ServiceProvider
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
        Session::macro('socialite', function (Provider $provider, string $email = null) {
            if (is_null($email)) {
                return $this->get('socialite.'.$provider->value);
            }

            $this->put('socialite.'.$provider->value, $email);
        });

        Session::macro('socialiteMissingAll', function () {
            return $this->missing('socialite');
        });
    }
}
