<?php

namespace Tests\Feature\Auth;

use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * 회원가입 폼 테스트
     *
     * @return void
     */
    public function testShowRegistrationForm()
    {
        $this->get('/register')
            ->assertOk()
            ->assertViewIs('auth.register');
    }

    /**
     * 회원가입 테스트
     *
     * @return void
     */
    public function testRegister()
    {
        Event::fake();

        $email = $this->faker->safeEmail;

        $response = $this->post('/register', [
            'name' => $this->faker->name,
            'email' => $email,
            'password' => 'password',
        ]);

        $response->assertRedirect(
            route('verification.notice')
        );

        $this->assertDatabaseHas('users', [
            'email' => $email,
        ]);

        $this->assertAuthenticated();

        Event::assertDispatched(Registered::class);
    }
}
