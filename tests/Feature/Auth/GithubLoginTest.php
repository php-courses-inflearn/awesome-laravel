<?php

namespace Tests\Feature\Auth;

use Database\Seeders\ProviderSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;
use Tests\TestCase;

class GithubLoginTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * 서비스 제공자 리다이렉트
     *
     * @return void
     */
    public function testRedirect()
    {
        $this->get('/login/github')
            ->assertRedirectContains('https://github.com/login/oauth/authorize');
    }

    /**
     * 소셜 로그인
     *
     * @return void
     */
    public function testCallback()
    {
        $this->seed(ProviderSeeder::class);

        $githubUser = $this->createStub(SocialiteUser::class);

        $githubUser->email = $this->faker->safeEmail;
        $githubUser->name = $this->faker->name;
        $githubUser->id = Str::random();
        $githubUser->token = Str::random(32);

        Socialite::shouldReceive('driver->user')
            ->once()
            ->andReturn($githubUser);

        $this->get('/login/github/callback')
            ->assertRedirect()
            ->assertSessionHas('Socialite');

        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'email' => $githubUser->email,
            'name' => $githubUser->name,
            'provider_uid' => $githubUser->id,
            'provider_token' => $githubUser->token,
        ]);
    }
}
