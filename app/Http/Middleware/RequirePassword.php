<?php

namespace App\Http\Middleware;

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
    protected function shouldConfirmPassword(Request $request, ?int $passwordTimeoutSeconds = null): bool
    {
        return session()->socialiteMissingAll()
            && parent::shouldConfirmPassword($request, $passwordTimeoutSeconds);
    }
}
