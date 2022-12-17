<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\ValidateSignature;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class EmailVerificationControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * 이메일이 인증되지 않은 경우 테스트
     *
     * @return void
     */
    public function testCreate()
    {
        $this->withoutMiddleware(Authenticate::class)
            ->get(route('verification.notice'))
            ->assertOk()
            ->assertViewIs('auth.verify-email');
    }

    /**
     * 이메일 인증 테스트
     *
     * @return void
     */
    public function testStore()
    {
        Notification::fake();

        $user = $this->user();

        $this->actingAs($user)
            ->post(route('verification.send'))
            ->assertRedirect();

        Notification::assertSentTo(
            $user, VerifyEmail::class);
    }

    /**
     * 이메일 인증 테스트
     *
     * @return void
     */
    public function testUpdate()
    {
        $user = $this->user();

        $this->actingAs($user)
            ->withoutMiddleware(ValidateSignature::class)
            ->get(route('verification.verify', [
                'id' => $user->getKey(),
                'hash' => sha1($user->getEmailForVerification()),
            ]))
            ->assertRedirect(RouteServiceProvider::HOME);

        $this->assertTrue($user->hasVerifiedEmail());
    }

    /**
     * User
     *
     * @return \App\Models\User
     */
    private function user()
    {
        $factory = User::factory()->unverified();

        return $factory->create();
    }
}
