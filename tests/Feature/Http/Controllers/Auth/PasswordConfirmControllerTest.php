<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PasswordConfirmControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * 비밀번호 확인 폼 테스트
     *
     * @return void
     */
    public function testCreate()
    {
        $user = $this->user();

        $this->actingAs($user)
            ->get(route('password.confirm'))
            ->assertOk()
            ->assertViewIs('auth.confirm-password');
    }

    /**
     * 비밀번호 확인 테스트
     */
    public function testStore()
    {
        $user = $this->user();

        $response = $this->actingAs($user)
            ->post(route('password.confirm'), [
                'password' => 'password',
            ]);

        $response->assertRedirect();
    }

    /**
     * 비밀번호 확인 실패 테스트
     *
     * @return void
     */
    public function testStoreFailed()
    {
        $user = $this->user();

        $response = $this->actingAs($user)
            ->post(route('password.confirm'), [
                'password' => $this->faker->password(8),
            ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('password');
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
