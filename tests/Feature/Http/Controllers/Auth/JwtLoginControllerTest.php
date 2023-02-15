<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPOpenSourceSaver\JWTAuth\JWT;
use Tests\TestCase;

class JwtLoginControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testCreateJwtForValidCredentials(): void
    {
        $user = User::factory()->create();

        $response = $this->post(route('jwt.login'), [
            'email' => $user->email,
            'password' => 'password',
        ])
        ->assertJson(function (AssertableJson $json) {
            $json->hasAll(['access_token', 'token_type', 'expires_in']);
        })
        ->assertSuccessful();

        $this->assertAuthenticated('api');

        $this->assertTrue(
            app(JWT::class)->setToken(
                $response->json()['access_token']
            )->check()
        );
    }

    public function testFailToCreateJwtForInvalidCredentials(): void
    {
        $user = User::factory()->create();

        $this->post(route('jwt.login'), [
            'email' => $user->email,
            'password' => $this->faker->password(8),
        ])
        ->assertJson(function (AssertableJson $json) {
            $json->has('error');
        })
        ->assertUnauthorized();

        $this->assertGuest('api');
    }

    public function testRefreshJwt(): void
    {
        $user = User::factory()->create();

        $token = auth('api')->login($user);

        $response = $this->withToken($token)
            ->put(route('jwt.refresh'))
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['access_token', 'token_type', 'expires_in']);
            })
            ->assertSuccessful();

        $this->assertTrue(
            app(JWT::class)->setToken(
                $response->json()['access_token']
            )->check()
        );

        $this->assertFalse(
            app(JWT::class)->setToken($token)->check()
        );
    }

    public function testDeleteJwt(): void
    {
        $user = User::factory()->create();

        $token = auth('api')->login($user);

        $this->withToken($token)
            ->delete(route('jwt.logout'))
            ->assertJson(function (AssertableJson $json) {
                $json->has('message');
            })
            ->assertSuccessful();

        $this->assertGuest('api');

        $this->assertFalse(
            app(JWT::class)->setToken($token)->check()
        );
    }
}
