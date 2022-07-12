<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use WithFaker;

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
     * 비밀번호를 찾을 이메일을 입력하는 폼 테스트
     *
     * @return void
     */
    public function testRequest()
    {
        $response = $this->get('/forgot-password');

        $response->assertViewIs('auth.forgot-password');
    }

    /**
     * 비밀번호 재설정 이메일 전송 테스트
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Factories\HasFactory|\Illuminate\Database\Eloquent\Model|mixed
     */
    public function testEmail()
    {
        Notification::fake();
        Mail::fake();

        /**
         * 이메일 보내기 실패
         */
        $response = $this->post('/forgot-password', [
            'email' => $this->faker->safeEmail
        ]);

        Mail::assertNothingSent();

        $response->assertRedirect();
        $response->assertSessionHasErrors('email');

        /**
         * 이메일 보내기
         */
        $user = User::factory()->create();

        $response = $this->post('/forgot-password', [
            'email' => $user->email
        ]);

        Notification::assertSentTo(
            [$user], ResetPassword::class
        );

        $response->assertRedirect();
        $response->assertSessionHas('status');

        // 토큰 재설정
        Password::deleteToken($user);
        $token = Password::createToken($user);

        return [$user, $token];
    }

    /**
     * 비밀번호 재설정 폼 테스트
     *
     * @depends testEmail
     */
    public function testReset(array $credentials)
    {
        [, $token] = $credentials;

        $response = $this->get("/reset-password/{$token}");

        $response->assertViewIs('auth.reset-password');
    }

    /**
     * 비밀번호 재설정 테스트
     *
     * @depends testEmail
     */
    public function testUpdate(array $credentials)
    {
        Event::fake();

        /**
         * 비밀번호 재설정 실패
         */
        $response = $this->post('/reset-password', [
            'email' => $this->faker->safeEmail,
            'password' => 'password',
            'password_confirmation' => 'password',
            'token' => Str::random()
        ]);

        Event::assertNotDispatched(PasswordReset::class);

        $response->assertRedirect();

        /**
         * 비밀번호 재설정
         */
        [$user, $token] = $credentials;

        $response = $this->post('/reset-password', [
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
            'token' => $token
        ]);

        $user->delete();

        Event::assertDispatched(PasswordReset::class);

        $response->assertRedirect();
    }
}
