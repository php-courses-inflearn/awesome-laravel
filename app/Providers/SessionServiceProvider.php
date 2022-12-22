<?php

namespace App\Providers;

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
        Session::macro('socialite', function ($provider, $uid = null) {
            if (is_null($uid)) {
                return $this->get('socialite.'.$provider);
            }

            $this->put('socialite.'.$provider, $uid);
        });

        Session::macro('socialiteMissingAll', function () {
            return $this->missing('socialite');
        });
    }
}
