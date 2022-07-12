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
     * 회원가입 폼 테스트
     *
     * @return void
     */
    public function testShowRegistrationForm()
    {
        $response = $this->get('/register');

        $response->assertViewIs('auth.register');
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
            'password' => 'password'
        ]);

        $this->assertDatabaseHas('users', [
            'email' => $email
        ]);

        $this->assertAuthenticated();

        Event::assertDispatched(Registered::class);

        $response->assertRedirect(
            route('verification.notice')
        );
    }
}
