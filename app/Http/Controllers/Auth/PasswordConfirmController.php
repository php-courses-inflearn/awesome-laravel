<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordConfirmRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class PasswordConfirmController extends Controller
{
    /**
     * 비밀번호 확인 폼
     */
    public function create(): View
    {
        return view('auth.confirm-password');
    }

    /**
     * 비밀번호 확인
     */
    public function store(PasswordConfirmRequest $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        if (! Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => __('auth.password'),
            ]);
        }

        $request->session()->passwordConfirmed();

        return redirect()->intended();
    }
}
