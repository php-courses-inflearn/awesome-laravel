<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * 회원가입 폼
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * 회원가입
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(RegisterUserRequest $request)
    {
        $user = User::create(
            $request->only(['name', 'email']) + [
                'password' => Hash::make($request->password),
            ]
        );

        auth()->login($user);

        event(new Registered($user));

        return to_route('verification.notice');
    }
}
