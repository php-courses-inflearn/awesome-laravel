<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Http\Middleware\RequirePassword;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * 마이페이지 테스트
     *
     * @return void
     */
    public function testShow()
    {
        $user = $this->user();

        $this->actingAs($user)
            ->withoutMiddleware(RequirePassword::class)
            ->get(route('profile.show'))
            ->assertOk()
            ->assertViewIs('auth.profile.show');
    }

    /**
     * 마이페이지 - 개인정보수정 테스트
     *
     * @return void
     */
    public function testEdit()
    {
        $user = $this->user();

        $this->actingAs($user)
            ->withoutMiddleware(RequirePassword::class)
            ->get(route('profile.edit'))
            ->assertOk()
            ->assertViewIs('auth.profile.edit');
    }

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
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $data
     * @param  string  $password
     * @return void
     */
    private function update(Authenticatable $user, array $data, string $password)
    {
        $this->actingAs($user)
            ->withoutMiddleware(RequirePassword::class)
            ->put(route('profile.update'), $data)
            ->assertRedirect(route('profile.show'));

        $this->assertTrue(
            Hash::check($password, $user->getAuthPassword())
        );

        $this->assertDatabaseHas('users', [
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
