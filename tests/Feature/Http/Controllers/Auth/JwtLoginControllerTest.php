<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPOpenSourceSaver\JWTAuth\JWTGuard;
use Tests\TestCase;

class JwtLoginControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * JWT 생성 테스트
     *
     * @return void
     */
    public function testStore()
    {
        $user = $this->user();

        $this->post(route('jwt.login'), [
            'email' => $user->email,
            'password' => 'password',
        ])
        ->assertJson(function (AssertableJson $json) {
            $json->hasAll(['access_token', 'token_type', 'expires_in']);
        })
        ->assertSuccessful();
    }

    /**
     * JWT 생성 실패 테스트
     *
     * @return void
     */
    public function testStoreFailed()
    {
        $user = $this->user();

        $this->post(route('jwt.login'), [
            'email' => $user->email,
            'password' => $this->faker->password(8),
        ])
        ->assertJson(function (AssertableJson $json) {
            $json->has('error');
        })
        ->assertUnauthorized();
    }

    /**
     * JWT 갱신 테스트
     *
     * @return void
     */
    public function testUpdate()
    {
        $user = $this->user();

        $token = $this->guard()->login($user);

        $this->withToken($token)
            ->put(route('jwt.refresh'))
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['access_token', 'token_type', 'expires_in']);
            })
            ->assertSuccessful();
    }

    /**
     * JWT 제거 테스트
     *
     * @return void
     */
    public function testDestroy()
    {
        $user = $this->user();

        $token = $this->guard()->login($user);

        $this->withToken($token)
            ->delete(route('jwt.logout'))
            ->assertJson(function (AssertableJson $json) {
                $json->has('message');
            })
            ->assertSuccessful();
    }

    /**
     * Guard
     *
     * @return \PHPOpenSourceSaver\JWTAuth\JWTGuard
     */
    private function guard()
    {
        /** @var JWTGuard $guard */
        $guard = auth('api');

        return $guard;
    }

    /**
     * User
     *
     * @return \App\Models\User
     */
    private function user()
    {
        $factory = User::factory();

        return $factory->create();
    }
}
