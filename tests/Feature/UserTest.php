<?php

namespace Tests\Feature;

use App\Http\Middleware\RequirePassword;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * 사용자 정보 갱신 테스트
     *
     * @param User $user
     * @return void
     */
    public function testUpdate()
    {
        $user = User::factory()->create();

        $data = [
            'name' => $this->faker->name
        ];

        /**
         * 비밀번호 변경 요청 제외
         */
        $this->update($user, $data, 'password');

        /**
         * 비밀번호 변경 요청
         */
        $password = $this->faker->password(8);
        $data = $data + [
                'password' => $password,
                'password_confirmation' => $password
            ];

        $this->update($user, $data, $password);
    }

    /**
     * 회원탈퇴 테스트
     *
     * @return void
     */
    public function testDestroy()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->withoutMiddleware(RequirePassword::class)
            ->delete('/user');

        $this->assertDatabaseMissing('users', [
            'email' => $user->email
        ]);

        $response->assertRedirect();
    }

    /**
     * 비밀번호 변경
     *
     * @param Authenticatable $user
     * @param array $data
     * @param string $password
     * @return void
     */
    private function update(Authenticatable $user, array $data, string $password)
    {
        $response = $this->actingAs($user)
            ->withoutMiddleware(RequirePassword::class)
            ->put('/user', $data);

        $this->assertTrue(
            Hash::check($password, $user->getAuthPassword())
        );

        $this->assertDatabaseHas($user, [
            'name' => $data['name']
        ]);

        $response->assertRedirect();
    }
}
