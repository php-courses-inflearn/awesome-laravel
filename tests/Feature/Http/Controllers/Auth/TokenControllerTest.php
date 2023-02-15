<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Enums\Ability;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TokenControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testReturnsCreateViewForToken(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('tokens.create'))
            ->assertOk()
            ->assertViewIs('tokens.create');
    }

    public function testCreateToken(): void
    {
        $user = User::factory()->create();

        $abilities = $this->faker->randomElements(
            collect(Ability::cases())->pluck('value')->toArray()
        );

        $name = $this->faker->word();

        $this->actingAs($user)
            ->post(route('tokens.store'), [
                'name' => $name,
                'abilities' => $abilities,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('personal_access_tokens', [
            'name' => $name,
            'abilities' => json_encode($abilities),
        ]);
    }

    public function testDeleteToken(): void
    {
        $user = User::factory()->create();

        $name = $this->faker->word();
        $user->createToken($name);

        $token = $user->tokens()->first();

        $this->actingAs($user)
            ->delete(route('tokens.destroy', $token))
            ->assertRedirect();

        $this->assertDatabaseMissing('personal_access_tokens', [
            'name' => $name,
        ]);
    }
}
