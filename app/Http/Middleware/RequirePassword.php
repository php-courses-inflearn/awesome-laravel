<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\RequirePassword as Middleware;

class RequirePassword extends Middleware
{
    /**
     * Determine if the confirmation timeout has expired.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int|null  $passwordTimeoutSeconds
     * @return bool
     */
    protected function shouldConfirmPassword($request, $passwordTimeoutSeconds = null)
    {
        if (auth()->check() && $request->user()->provider) {
            return false;
        }

        return parent::shouldConfirmPassword($request, $passwordTimeoutSeconds);
    }
}
