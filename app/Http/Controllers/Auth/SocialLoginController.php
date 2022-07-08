<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use App\Enums\Provider as ProviderEnum;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Contracts\User as SocialUser;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    /**
     * @var ProviderEnum 서비스 제공자
     */
    protected ProviderEnum $provider;

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

        return redirect()->intended();
    }

    /**
     * 소셜 사용자 등록
     *
     * @param SocialUser $socialUser
     * @return User
     */
    protected function register(SocialUser $socialUser): User
    {
        $user = Provider::find($this->provider)->users()->updateOrCreate([
            'email' => $socialUser->email
        ], [
            'name' => $socialUser->name,
            'provider_uid' => $socialUser->id,
            'provider_token' => $socialUser?->token,
            'provider_refresh_token' => $socialUser?->refreshToken
        ]);

        $user->markEmailAsVerified();

        return $user;
    }
}
