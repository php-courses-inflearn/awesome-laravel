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
        $response = $this->withoutMiddleware(Authenticate::class)
            ->get('/confirm-password');

        $response->assertViewIs('auth.confirm-password');
    }

    /**
     * 비밀번호 확인 테스트
     */
    public function testConfirm()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post('/confirm-password', [
                'password' => $this->faker->password(8)
            ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('password');

        $response = $this->post('/confirm-password', [
            'password' => 'password'
        ]);

        $response->assertRedirect();
    }
}
