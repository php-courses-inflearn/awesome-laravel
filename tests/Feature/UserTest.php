<?php

namespace Tests\Feature;

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
     * @return void
     */
    public function testUpdate()
    {
        $user = $this->user();

        $data = [
            'name' => $this->faker->name,
        ];

        $this->update($user, $data, 'password');
    }

    /**
     * 사용자 정보 갱신 (비밀번호) 테스트
     *
     * @return void
     */
    public function testUpdateWithPassword()
    {
        $user = $this->user();

        $data = [
            'name' => $this->faker->name,
        ];

        $password = $this->faker->password(8);
        $data = $data + [
            'password' => $password,
            'password_confirmation' => $password,
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
        $user = $this->user();

        $this->actingAs($user)
            ->delete('/user')
            ->assertRedirect();

        $this->assertDatabaseMissing('users', [
            'email' => $user->email,
        ]);
    }

    /**
     * 비밀번호 변경
     *
     * @param  Authenticatable  $user
     * @param  array  $data
     * @param  string  $password
     * @return void
     */
    private function update(Authenticatable $user, array $data, string $password)
    {
        $this->actingAs($user)
            ->put('/user', $data)
            ->assertRedirect();

        $this->assertTrue(
            Hash::check($password, $user->getAuthPassword())
        );

        $this->assertDatabaseHas($user, [
            'name' => $data['name'],
        ]);
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
