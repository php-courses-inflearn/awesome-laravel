<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserControllerTest extends TestCase
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
        $password = $this->faker->password(8);

        $data = [
            'name' => $this->faker->name,
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
            ->delete(route('user.destroy'))
            ->assertRedirect();

        $this->assertDatabaseMissing('users', [
            'email' => $user->email,
        ]);
    }

    /**
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $data
     * @param  string  $password
     * @return void
     */
    private function update(Authenticatable $user, array $data, string $password)
    {
        $this->actingAs($user)
            ->put(route('user.update'), $data)
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
     * @return \App\Models\User
     */
    private function user()
    {
        $factory = User::factory();

        return $factory->create();
    }
}
