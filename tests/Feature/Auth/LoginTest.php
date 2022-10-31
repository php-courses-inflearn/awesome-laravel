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
     * 로그인 폼 테스트
     *
     * @return void
     */
    public function testShowLoginForm()
    {
        $this->get('/login')
            ->assertOk()
            ->assertViewIs('auth.login');
    }

    /**
     * 로그인 테스트
     *
     * @return void
     */
    public function testLogin()
    {
        $user = $this->user();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();

        $response->assertRedirect();
    }

    /**
     * 로그인 실패 테스트
     *
     * @return void
     */
    public function testLoginFailed()
    {
        $user = $this->user();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => $this->faker->password(8),
        ]);

        $this->assertGuest();

        $response->assertRedirect();
        $response->assertSessionHasErrors('failed');
    }

    /**
     * Ajax 로그인 테스트
     *
     * @return void
     */
    public function testLoginWithAjax()
    {
        $user = $this->user();

        $response = $this->postJson('/login', [
            'email' => $user->email,
            'password' => 'password',
        ], [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);

        $this->assertAuthenticated();

        $response->assertOk();
    }

    /**
     * 로그아웃 테스트
     *
     * @return void
     */
    public function testLogout()
    {
        $user = $this->user();

        $this->actingAs($user)
            ->post('/logout')
            ->assertRedirect();

        $this->assertGuest();
    }

    /**
     * User
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Factories\HasFactory|\Illuminate\Database\Eloquent\Model|mixed
     */
    private function user()
    {
        $factory = User::factory();

        return $factory->create();
    }
}
