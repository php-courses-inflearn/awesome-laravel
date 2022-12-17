<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * 로그인 폼 테스트
     *
     * @return void
     */
    public function testCreate()
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertViewIs('auth.login');
    }

    /**
     * 로그인 테스트
     *
     * @return void
     */
    public function testStore()
    {
        $user = $this->user();

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ])
        ->assertRedirect();

        $this->assertAuthenticated();
    }

    /**
     * 로그인 실패 테스트
     *
     * @return void
     */
    public function testStoreFailed()
    {
        $user = $this->user();

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => $this->faker->password(8),
        ])
        ->assertRedirect()
        ->assertSessionHasErrors('failed');

        $this->assertGuest();
    }

    /**
     * Ajax 로그인 테스트
     *
     * @return void
     */
    public function testStoreWithAjax()
    {
        $user = $this->user();

        $this->postJson(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ], [
            'X-Requested-With' => 'XMLHttpRequest',
        ])
        ->assertOk();

        $this->assertAuthenticated();
    }

    /**
     * 로그아웃 테스트
     *
     * @return void
     */
    public function testDestroy()
    {
        $user = $this->user();

        $this->actingAs($user)
            ->post(route('logout'))
            ->assertRedirect(RouteServiceProvider::HOME);

        $this->assertGuest();
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
