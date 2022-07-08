<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Provider as ProviderEnum;

class GithubLoginController extends SocialLoginController
{
    /**
     * @var ProviderEnum 서비스 제공자
     */
    protected ProviderEnum $provider = ProviderEnum::GITHUB;
}
