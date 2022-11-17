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

    /**
     * 비밀번호를 찾을 이메일을 입력하는 폼 테스트
     *
     * @return void
     */
    public function testCreate()
    {
        $this->get(route('password.request'))
            ->assertOk()
            ->assertViewIs('auth.forgot-password');
    }

    /**
     * 비밀번호 재설정 이메일 전송 테스트
     *
     * @return void
     */
    public function testStore()
    {
        Notification::fake();

        $user = $this->user();

        $response = $this->post(route('password.email'), [
            'email' => $user->email,
        ]);

        Notification::assertSentTo(
            $user, ResetPassword::class
        );

        $response->assertRedirect();
        $response->assertSessionHas('status');
    }

    /**
     * 비밀번호 재설정 이메일 전송 실패 테스트
     *
     * @return void
     */
    public function testStoreFailed()
    {
        Mail::fake();

        $response = $this->post(route('password.email'), [
            'email' => $this->faker->safeEmail,
        ]);

        Mail::assertNothingSent();

        $response->assertRedirect();
        $response->assertSessionHasErrors('email');
    }

    /**
     * 비밀번호 재설정 폼 테스트
     */
    public function testEdit()
    {
        $token = Str::random(32);

        $this->get(route('password.reset', [
            'token' => $token,
        ]))
        ->assertOk()
        ->assertViewIs('auth.reset-password');
    }

    /**
     * 비밀번호 재설정 테스트
     */
    public function testUpdate()
    {
        Event::fake();

        $user = $this->user();

        $token = Password::createToken($user);

        $response = $this->post(route('password.update'), [
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
            'token' => $token,
        ]);

        Event::assertDispatched(PasswordReset::class);

        $response->assertRedirect();
        $response->assertSessionHas('status');
    }

    /**
     * 비밀번호 재설정 실패 테스트
     *
     * @return void
     */
    public function testUpdateFailed()
    {
        Event::fake();

        $response = $this->post(route('password.update'), [
            'email' => $this->faker->safeEmail,
            'password' => 'password',
            'password_confirmation' => 'password',
            'token' => Str::random(),
        ]);

        Event::assertNotDispatched(PasswordReset::class);

        $response->assertRedirect();
        $response->assertSessionHasErrors('email');
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
