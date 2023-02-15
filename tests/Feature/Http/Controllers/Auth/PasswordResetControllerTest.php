<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Tests\TestCase;

class PasswordResetControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testReturnsForgotPasswordView(): void
    {
        $this->get(route('password.request'))
            ->assertOk()
            ->assertViewIs('auth.forgot-password');
    }

    public function testSendEmailForPasswordResets(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post(route('password.email'), [
            'email' => $user->email,
        ])
        ->assertRedirect()
        ->assertSessionHas('status');

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function testFailToSendEmailForPasswordResets(): void
    {
        Mail::fake();

        $this->post(route('password.email'), [
            'email' => $this->faker->safeEmail(),
        ])
        ->assertRedirect()
        ->assertSessionHasErrors('email');

        Mail::assertNothingSent();
    }

    public function testReturnsResetPasswordView(): void
    {
        $token = Str::random(32);

        $this->get(route('password.reset', [
            'token' => $token,
        ]))
        ->assertOk()
        ->assertViewIs('auth.reset-password');
    }

    public function testPasswordResetsForValidToken(): void
    {
        Event::fake();

        $user = User::factory()->create();

        $token = Password::createToken($user);

        $this->post(route('password.update'), [
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
            'token' => $token,
        ])
        ->assertRedirect()
        ->assertSessionHas('status');

        Event::assertDispatched(PasswordReset::class);
    }

    public function testFailToPasswordResetsForInvalidToken(): void
    {
        Event::fake();

        $this->post(route('password.update'), [
            'email' => $this->faker->safeEmail(),
            'password' => 'password',
            'password_confirmation' => 'password',
            'token' => Str::random(),
        ])
        ->assertRedirect()
        ->assertSessionHasErrors('email');

        Event::assertNotDispatched(PasswordReset::class);
    }
}
