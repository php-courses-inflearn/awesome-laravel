<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Contracts\User as SocialiteUser;

class GithubLoginController extends Controller
{
    /**
     * 깃허브 인증 페이지로 리다이렉트
     *
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirect()
    {
        return Socialite::driver('github')->redirect();
    }

    /**
     * 깃허브 로그인
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callback()
    {
        $githubUser = Socialite::driver('github')->user();
        $user = $this->register($githubUser);

        auth()->login($user);

        return redirect()->intended();
    }

    /**
     * 깃허브 사용자 등록
     *
     * @param SocialiteUser $githubUser
     * @return User
     */
    private function register(SocialiteUser $githubUser): User
    {
        $user = User::updateOrCreate([
            'email' => $githubUser->email // $githubUser->getEmail()
        ], [
            'name' => $githubUser->name // $githubUser->getName()
        ]);

        $user->githubUser()->updateOrCreate([
            'id' => $githubUser->id // $githubUser->getId()
        ], [
            'token' => $githubUser->token
        ]);

        $user->markEmailAsVerified();

        return $user;
    }
}
