<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        \URL::forceRootUrl(env('APP_AUTH_URL'));
    }

    /**
     * 로그인 폼 테스트
     *
     * @return void
     */
    public function testShowLoginForm()
    {
        $response = $this->get('/');

        $response->assertViewIs('auth.login');
    }

    /**
     * 로그인 테스트
     *
     * @return void
     */
    public function testLogin()
    {
        $user = User::factory()->create();

        /**
         * 로그인 실패
         */
        $response = $this->post('/', [
            'email' => $user->email,
            'password' => $this->faker->password(8)
        ]);

        $this->assertGuest();

        $response->assertRedirect();
        $response->assertSessionHasErrors('failed');

        /**
         * 로그인
         */
        $response = $this->post('/', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $this->assertAuthenticated();

        $response->assertRedirect();
    }

    /**
     * 로그아웃 테스트
     *
     * @return void
     */
    public function testLogout()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post('/logout');

        $this->assertGuest();

        $response->assertRedirect();
    }
}
