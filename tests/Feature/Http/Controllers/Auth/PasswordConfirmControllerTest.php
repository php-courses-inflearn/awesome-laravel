<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PasswordConfirmControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testReturnsPasswordConfirmView(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('password.confirm'))
            ->assertOk()
            ->assertViewIs('auth.confirm-password');
    }

    public function testConfirmsPasswordForCorrectPassword(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('password.confirm'), [
                'password' => 'password',
            ])
            ->assertRedirect();
    }

    public function testFailToConfirmPasswordForIncorrectPassword(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('password.confirm'), [
                'password' => $this->faker->password(8),
            ])
            ->assertRedirect()
            ->assertSessionHasErrors('password');
    }
}
