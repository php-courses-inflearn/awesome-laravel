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
    public function shouldConfirmPassword($request, $passwordTimeoutSeconds = null)
    {
        return auth()->check() && (! $request->user()->provider)
            && parent::shouldConfirmPassword($request, $passwordTimeoutSeconds);
    }
}
