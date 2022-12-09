<?php

namespace App\Http\Controllers\Auth;

use App\Enums\SocialiteProvider;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;

class LoginController extends Controller
{
    /**
     * 로그인 폼
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login', [
            'providers' => SocialiteProvider::cases(),
        ]);
    }

    /**
     * 로그인
     *
     * @param  \App\Http\Requests\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function store(LoginRequest $request)
    {
        if (! auth()->attempt($request->validated(), $request->boolean('remember'))) {
            return back()->withErrors([
                'failed' => __('auth.failed'),
            ]);
        }

        if ($request->ajax()) {
            return response()->json('');
        }

        return redirect()->intended();
    }

    /**
     * 로그아웃
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy()
    {
        auth()->logout();

        session()->invalidate();
        session()->regenerateToken();

        return redirect()->to('/');
    }
}
