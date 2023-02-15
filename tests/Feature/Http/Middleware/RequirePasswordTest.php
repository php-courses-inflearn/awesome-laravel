<?php

namespace Tests\Feature\Http\Middleware;

use App\Enums\Provider;
use App\Http\Middleware\RequirePassword;
use Illuminate\Contracts\Session\Session;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Tests\TestCase;

class RequirePasswordTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testRequirePasswordRedirect()
    {
        /** @var \App\Http\Middleware\RequirePassword $requirePasswordMiddleware */
        $requirePasswordMiddleware = app(RequirePassword::class);

        $request = app(Request::class);
        $request->setLaravelSession(app(Session::class));

        $response = $requirePasswordMiddleware->handle($request, function () {
        });

        $this->assertEquals($response->getStatusCode(), 302);
    }

    public function testRequirePasswordDoesNotRedirect()
    {
        /** @var \App\Http\Middleware\RequirePassword $requirePasswordMiddleware */
        $requirePasswordMiddleware = app(RequirePassword::class);

        $request = app(Request::class);
        $request->setLaravelSession(app(Session::class));
        $request->session()->socialite(Provider::Github, $this->faker->safeEmail());

        $response = $requirePasswordMiddleware->handle($request, function () {
        });

        $this->assertEquals($response, null);
    }
}
