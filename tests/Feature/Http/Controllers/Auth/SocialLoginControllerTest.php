<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Enums\SocialiteProvider;
use App\Models\Provider;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;
use Mockery\MockInterface;
use Tests\TestCase;

class SocialLoginControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * 서비스 제공자 리다이렉트
     *
     * @return void
     */
    public function testCreate()
    {
        $provider = $this->provider(SocialiteProvider::Github);

        /** @see \Laravel\Socialite\Two\GithubProvider::getAuthUrl() */
        $this->get(route('login.social', $provider))
            ->assertRedirectContains('https://github.com/login/oauth/authorize');
    }

    /**
     * 소셜 로그인
     *
     * @return void
     */
    public function testStore()
    {
        $provider = $this->provider(SocialiteProvider::Github);

        $data = [
            'email' => $this->faker->safeEmail,
            'name' => $this->faker->name,
            'provider_uid' => Str::random(),
        ];

        $socialUser = $this->mock(SocialiteUser::class, function (MockInterface $mock) use ($data) {
            $mock->shouldReceive('getEmail')
                ->once()
                ->andReturn($data['email']);
            $mock->shouldReceive('getName')
                ->once()
                ->andReturn($data['name']);
            $mock->shouldReceive('getId')
                ->once()
                ->andReturn($data['provider_uid']);
        });

        Socialite::shouldReceive('driver->user')
            ->once()
            ->andReturn($socialUser);

        $this->get(route('login.social.callback', $provider))
            ->assertRedirect()
            ->assertSessionHas('auth.socialite');

        $this->assertAuthenticated();

        $this->assertCount(1, $provider->users);

        $this->assertDatabaseHas('users', [
            ...$data,
            //'provider_id' => $provider->id,
            'provider_id' => User::firstWhere('email', $data['email'])->provider->id,
        ]);
    }

    /**
     * @param  \App\Enums\SocialiteProvider  $provider
     * @return \App\Models\Provider
     */
    private function provider(SocialiteProvider $provider)
    {
        return Provider::create([
            'name' => $provider->value,
        ]);
    }
}
