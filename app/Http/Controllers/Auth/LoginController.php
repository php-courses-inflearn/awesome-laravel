<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Provider;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * 로그인 폼
     */
    public function create(): View
    {
        return view('auth.login', [
            'providers' => Provider::cases(),
        ]);
    }

    /**
     * 로그인
     */
    public function store(LoginRequest $request): RedirectResponse|JsonResponse
    {
        if (! auth()->attempt($request->validated(), $request->boolean('remember'))) {
            return back()->withErrors([
                'failed' => __('auth.failed'),
            ]);
        }

        if ($request->ajax()) {
            return response()->json(['message' => 'Successfully logged in']);
        }

        return redirect()->intended();
    }

    /**
     * 로그아웃
     */
    public function destroy(): RedirectResponse
    {
        auth()->logout();

        session()->invalidate();
        session()->regenerateToken();

        return redirect()->to(RouteServiceProvider::HOME);
    }
}
