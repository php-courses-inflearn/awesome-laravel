<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class LoginController extends Controller
{
    /**
     * 로그인 폼
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * 로그인
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users|max:255',
            'password' => 'required|max:255'
        ]);

        $request->validate([
            'password' => [Password::defaults()]
        ]);

        $credentials = $request->only(['email', 'password']);

        if (! auth()->attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'failed' => __('auth.failed')
            ]);
        }

        return redirect()->intended();
    }

    /**
     * 로그아웃
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        auth()->logout();

        session()->invalidate();
        session()->regenerateToken();

        return to_route('home');
    }
}
