<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\SendResetLinkRequest;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    /**
     * 비밀번호를 찾을 이메일을 입력하는 폼
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.forgot-password');
    }

    /**
     * 비밀번호 재설정 이메일 전송
     *
     * @param  SendResetLinkRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(SendResetLinkRequest $request)
    {
        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * 비밀번호 재설정 폼
     *
     * @param  string  $token
     * @return \Illuminate\View\View
     */
    public function edit(string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
        ]);
    }

    /**
     * 비밀번호 재설정
     *
     * @param  ResetPasswordRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ResetPasswordRequest $request)
    {
        $credentials = $request->only(
            'email',
            'password',
            'password_confirmation',
            'token'
        );

        $status = Password::reset($credentials, function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password),
            ])->setRememberToken(Str::random(60));

            $user->save();

            event(new PasswordReset($user));
        });

        return $status === Password::PASSWORD_RESET
            ? to_route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
