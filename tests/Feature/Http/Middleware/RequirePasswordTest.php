<?php

namespace Tests\Feature\Http\Middleware;

use App\Enums\SocialiteProvider;
use App\Http\Middleware\RequirePassword;
use Illuminate\Contracts\Session\Session;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class RequirePasswordTest extends TestCase
{
    use RefreshDatabase;

    /**
     * RequirePassword 미들웨어 테스트
     *
     * @return void
     */
    public function testRequirePassword()
    {
        /** @var \App\Http\Middleware\RequirePassword $requirePasswordMiddleware */
        $requirePasswordMiddleware = app(RequirePassword::class);

        $request = app(Request::class);
        $request->setLaravelSession(app(Session::class));

        $response = $requirePasswordMiddleware->handle($request, function () {
        });

        $this->assertEquals($response->getStatusCode(), 302);
    }

    /**
     * RequirePassword 미들웨어 테스트 (Socialite)
     *
     * @return void
     */
    public function testRequirePasswordNotRedirect()
    {
        /** @var \App\Http\Middleware\RequirePassword $requirePasswordMiddleware */
        $requirePasswordMiddleware = app(RequirePassword::class);

        $request = app(Request::class);
        $request->setLaravelSession(app(Session::class));
        $request->session()->put('auth.socialite', true);

        $response = $requirePasswordMiddleware->handle($request, function () {
        });

        $this->assertEquals($response, null);
    }
}
