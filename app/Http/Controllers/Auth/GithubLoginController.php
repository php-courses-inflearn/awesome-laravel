<?php

namespace App\Http\Controllers\Auth;

use App\Enums\SocialiteProvider;

class GithubLoginController extends SocialLoginController
{
    /**
     * @var SocialiteProvider 서비스 제공자
     */
    protected SocialiteProvider $provider = SocialiteProvider::GITHUB;
}
