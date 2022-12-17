<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordConfirmRequest;
use Illuminate\Support\Facades\Hash;

class PasswordConfirmController extends Controller
{
    /**
     * 비밀번호 확인 폼
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.confirm-password');
    }

    /**
     * 비밀번호 확인
     *
     * @param  \App\Http\Requests\PasswordConfirmRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PasswordConfirmRequest $request)
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
