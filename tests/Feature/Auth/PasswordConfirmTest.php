<?php

namespace Tests\Feature\Auth;

use App\Http\Middleware\Authenticate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PasswordConfirmTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * 비밀번호 확인 폼 테스트
     *
     * @return void
     */
    public function testShowPasswordConfirmationForm()
    {
        $user = $this->user();

        $this->actingAs($user)
            ->get('/confirm-password')
            ->assertOk()
            ->assertViewIs('auth.confirm-password');
    }

    /**
     * 비밀번호 확인 테스트
     */
    public function testConfirm()
    {
        $user = $this->user();

        $response = $this->actingAs($user)
            ->post('/confirm-password', [
                'password' => 'password'
            ]);

        $response->assertRedirect();
    }

    /**
     * 비밀번호 확인 실패 테스트
     *
     * @return void
     */
    public function testConfirmFailed()
    {
        $user = $this->user();

        $response = $this->actingAs($user)
            ->post('/confirm-password', [
                'password' => $this->faker->password(8)
            ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('password');
    }

    /**
     * User
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Factories\HasFactory|\Illuminate\Database\Eloquent\Model|mixed
     */
    private function user()
    {
        $factory = User::factory();

        return $factory->create();
    }
}
