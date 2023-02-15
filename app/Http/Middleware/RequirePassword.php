<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\RequirePassword as Middleware;
use Illuminate\Http\Request;

class RequirePassword extends Middleware
{
    /**
     * Determine if the confirmation timeout has expired.
     */
    protected function shouldConfirmPassword(Request $request, ?int $passwordTimeoutSeconds = null): bool
    {
        return session()->socialiteMissingAll()
            && parent::shouldConfirmPassword($request, $passwordTimeoutSeconds);
    }
}
