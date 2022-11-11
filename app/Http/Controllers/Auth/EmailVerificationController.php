<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    /**
     * 이메일이 인증되지 않은 경우
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.verify-email');
    }

    /**
     * 인증 이메일 재전송
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $user->sendEmailVerificationNotification();

        return back();
    }

    /**
     * 이메일 인증
     *
     * @param  \Illuminate\Foundation\Auth\EmailVerificationRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return redirect()->to('/');
    }
}
