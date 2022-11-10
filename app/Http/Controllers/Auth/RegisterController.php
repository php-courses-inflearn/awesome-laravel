<?php

namespace App\Http\Controllers\Auth;

use App\Enums\SocialiteProvider;
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
    public function create()
    {
        return view('auth.register', [
            'providers' => SocialiteProvider::cases(),
        ]);
    }

    /**
     * 회원가입
     *
     * @param  RegisterUserRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(RegisterUserRequest $request)
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
