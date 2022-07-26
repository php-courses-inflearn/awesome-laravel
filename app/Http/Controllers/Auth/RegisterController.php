<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Rules\Password as PasswordRule;
use Illuminate\Validation\Rules\Password;

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
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|max:255'
        ]);

        $request->validate([
            'password' => [Password::defaults()]
        ]);

        $user = User::create(
            $request->only(['name', 'email']) + [
                'password' => Hash::make($request->password)
            ]
        );

        auth()->login($user);

        event(new Registered($user));

        return to_route('verification.notice');
    }
}
