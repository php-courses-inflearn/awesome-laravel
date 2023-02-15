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

    public function testReturnsLoginView(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertViewIs('auth.login');
    }

    public function testLoginForValidCredentials(): void
    {
        $user = User::factory()->create();

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ])
        ->assertRedirect();

        $this->assertAuthenticated();
    }

    public function testFailToLoginForInvalidCredentials(): void
    {
        $user = User::factory()->create();

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => $this->faker->password(8),
        ])
        ->assertRedirect()
        ->assertSessionHasErrors('failed');

        $this->assertGuest();
    }

    public function testAjaxLoginForValidCredentials(): void
    {
        $user = User::factory()->create();

        $this->postJson(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ], [
            'X-Requested-With' => 'XMLHttpRequest',
        ])
        ->assertOk();

        $this->assertAuthenticated();
    }

    public function testLogout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('logout'))
            ->assertRedirect(RouteServiceProvider::HOME);

        $this->assertGuest();
    }
}
