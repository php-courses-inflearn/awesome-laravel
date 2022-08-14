<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use App\Enums\SocialiteProvider;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    /**
     * @var SocialiteProvider 서비스 제공자
     */
    protected SocialiteProvider $provider;

    /**
     * 서비스 제공자 리다이렉트
     *
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirect()
    {
        return Socialite::driver($this->provider->name)->redirect();
    }

    /**
     * 소셜 로그인
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callback()
    {
        $socialUser = Socialite::driver($this->provider->name)->user();
        $user = $this->register($socialUser);

        auth()->login($user);

        session()->put('Socialite', $this->provider->name);

        return redirect()->intended();
    }

    /**
     * 소셜 사용자 등록
     *
     * @param SocialiteUser $socialUser
     * @return User
     */
    protected function register(SocialiteUser $socialUser)
    {
        $user = User::updateOrCreate([
            'email' => $socialUser->email
        ], [
            'name' => $socialUser->name,
            'provider_id' => $this->provider->value,
            'provider_uid' => $socialUser->id,
            'provider_token' => $socialUser->token
        ]);

        if ($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return $user;
    }
}
