<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    /**
     * 서비스 제공자 리다이렉트
     *
     * @param  \App\Models\Provider  $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(Provider $provider)
    {
        return Socialite::driver($provider->name)->redirect();
    }

    /**
     * 소셜 로그인
     *
     * @param  \App\Models\Provider  $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Provider $provider)
    {
        $socialUser = Socialite::driver($provider->name)->user();
        $user = $this->register($provider, $socialUser);

        auth()->login($user);

        session()->put('Socialite', $provider->name);

        return redirect()->intended();
    }

    /**
     * 소셜 사용자 등록
     *
     * @param  \App\Models\Provider  $provider
     * @param  \Laravel\Socialite\Contracts\User  $socialUser
     * @return \App\Models\User
     */
    private function register(Provider $provider, SocialiteUser $socialUser)
    {
        $user = User::updateOrCreate([
            'email' => $socialUser->getEmail(),
        ], [
            'name' => $socialUser->getName(),
            'provider_uid' => $socialUser->getId(),
        ]);

        $provider->users()->save($user);

        if ($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return $user;
    }
}
