<?php

namespace App\Http\Controllers\Auth;

use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Enums\Provider;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * 회원가입 폼
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        return view('auth.register', [
            'providers' => Provider::cases(),
        ]);
    }

    /**
     * 회원가입
     *
     * @param  RegisterUserRequest  $request
     * @return \Illuminate\Http\RedirectResponse
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
