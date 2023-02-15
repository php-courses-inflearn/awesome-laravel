<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Http\Middleware\RequirePassword;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testReturnsShowView(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->withoutMiddleware(RequirePassword::class)
            ->get(route('profile.show'))
            ->assertOk()
            ->assertViewIs('auth.profile.show');
    }

    public function testReturnsEditView(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->withoutMiddleware(RequirePassword::class)
            ->get(route('profile.edit'))
            ->assertOk()
            ->assertViewIs('auth.profile.edit');
    }

    public function testUpdate(): void
    {
        $user = User::factory()->create();

        $data = [
            'name' => $this->faker->name(),
        ];

        $this->actingAs($user)
            ->withoutMiddleware(RequirePassword::class)
            ->put(route('profile.update'), $data)
            ->assertRedirect(route('profile.show'));

        $this->assertTrue(
            Hash::check('password', $user->getAuthPassword())
        );

        $this->assertDatabaseHas('users', [
            'name' => $data['name'],
        ]);
    }

    public function testUpdateContainsPassword(): void
    {
        $user = User::factory()->create();
        $password = $this->faker->password(8);

        $data = [
            'name' => $this->faker->name(),
            'password' => $password,
            'password_confirmation' => $password,
        ];

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
}
