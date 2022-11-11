<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
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
