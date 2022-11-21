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

    /**
     * 회원가입 폼 테스트
     *
     * @return void
     */
    public function testCreate()
    {
        $this->get(route('register'))
            ->assertOk()
            ->assertViewIs('auth.register');
    }

    /**
     * 회원가입 테스트
     *
     * @return void
     */
    public function testStore()
    {
        Event::fake();

        $email = $this->faker->safeEmail;

        $this->post(route('register'), [
            'name' => $this->faker->name,
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
