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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function showPasswordConfirmationForm()
    {
        return view('auth.confirm-password');
    }

    /**
     * 비밀번호 확인
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirm(Request $request)
    {
        $user = $request->user();

        if (! Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => __('auth.password')
            ]);
        }

        $request->session()->passwordConfirmed();

        return redirect()->intended();
    }
}
