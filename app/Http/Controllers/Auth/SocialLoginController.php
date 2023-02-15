<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\RedirectResponse;
use App\Enums\Provider;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    /**
     * 서비스 제공자 리다이렉트
     *
     * @param  \App\Enums\Provider  $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(Provider $provider): RedirectResponse
    {
        return Socialite::driver($provider->value)->redirect();
    }

    /**
     * 소셜 로그인
     *
     * @param  \App\Enums\Provider  $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Provider $provider): RedirectResponse
    {
        $socialUser = Socialite::driver($provider->value)->user();
        $user = $this->register($socialUser);

        auth()->login($user);

        session()->socialite($provider, $socialUser->getEmail());

        return redirect()->intended();
    }

    /**
     * 소셜 사용자 등록
     *
     * @param  \Laravel\Socialite\Contracts\User  $socialUser
     * @return \App\Models\User
     */
    private function register(SocialiteUser $socialUser): User
    {
        $user = User::updateOrCreate([
            'email' => $socialUser->getEmail(),
        ], [
            'name' => $socialUser->getName(),
        ]);

        if ($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return $user;
    }
}
