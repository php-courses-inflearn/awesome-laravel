<?php

namespace Tests\Feature\Http\Middleware;

use App\Enums\Provider;
use App\Http\Middleware\RequirePassword;
use Illuminate\Contracts\Session\Session;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Tests\TestCase;

class RequirePasswordTest extends TestCase
{
    use WithFaker;

    public function testRequirePasswordRedirect(): void
    {
        /** @var \App\Http\Middleware\RequirePassword $requirePasswordMiddleware */
        $requirePasswordMiddleware = app(RequirePassword::class);

        /** @var \Illuminate\Http\Request $request */
        $request = app(Request::class);
        $request->setLaravelSession(app(Session::class));

        $response = $requirePasswordMiddleware->handle($request, function () {
        });

        $this->assertEquals($response->getStatusCode(), 302);
    }

    public function testRequirePasswordDoesNotRedirect(): void
    {
        /** @var \App\Http\Middleware\RequirePassword $requirePasswordMiddleware */
        $requirePasswordMiddleware = app(RequirePassword::class);

        /** @var \Illuminate\Http\Request $request */
        $request = app(Request::class);
        $request->setLaravelSession(app(Session::class));
        $request->session()->socialite(Provider::Github, $this->faker->safeEmail());

        $response = $requirePasswordMiddleware->handle($request, function () {
        });

        $this->assertEquals($response, null);
    }
}
