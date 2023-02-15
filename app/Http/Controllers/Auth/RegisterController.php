<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Provider;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /**
     * 회원가입 폼
     */
    public function create(): View
    {
        return view('auth.register', [
            'providers' => Provider::cases(),
        ]);
    }

    /**
     * 회원가입
     */
    public function store(RegisterUserRequest $request): RedirectResponse
    {
        $user = User::create([
            ...$request->validated(),
            'password' => Hash::make($request->password),
        ]);

        auth()->login($user);

        event(new Registered($user));

        return to_route('verification.notice');
    }
}
