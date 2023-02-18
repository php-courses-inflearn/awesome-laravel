<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Provider;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirectResponse;

class SocialLoginController extends Controller
{
    /**
     * 서비스 제공자 리다이렉트
     */
    public function create(Provider $provider): SymfonyRedirectResponse
    {
        return Socialite::driver($provider->value)->redirect();
    }

    /**
     * 소셜 로그인
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
