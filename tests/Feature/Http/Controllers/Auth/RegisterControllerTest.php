<?php

namespace Tests\Feature\Http\Controllers\Auth;

use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testReturnsRegisterView(): void
    {
        $this->get(route('register'))
            ->assertOk()
            ->assertViewIs('auth.register');
    }

    public function testUserRegistration(): void
    {
        Event::fake();

        $email = $this->faker->safeEmail();

        $this->post(route('register'), [
            'name' => $this->faker->name(),
            'email' => $email,
            'password' => 'password',
        ])
        ->assertRedirect(
            route('verification.notice')
        );

        $this->assertDatabaseHas('users', [
            'email' => $email,
        ]);

        $this->assertAuthenticated();

        Event::assertDispatched(Registered::class);
    }
}
