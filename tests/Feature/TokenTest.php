<?php

namespace Tests\Feature;

use App\Enums\TokenAbility;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TokenTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * 토큰 생성 폼 테스트
     *
     * @return void
     */
    public function testCreate()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/tokens/create')
            ->assertSuccessful();
    }

    /**
     * 토큰 생성 테스트
     *
     * @return void
     */
    public function testStore()
    {
        $user = User::factory()->create();

        $name = $this->faker->word;

        $abilities = $this->faker->randomElements(
            collect(TokenAbility::cases())->pluck('value')->toArray()
        );

        $this->actingAs($user)
            ->post('/tokens', [
                'name' => $name,
                'abilities' => $abilities
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('personal_access_tokens', [
            'name' => $name,
            'abilities' => json_encode($abilities)
        ]);
    }

    /**
     * 토큰 삭제 테스트
     *
     * @return void
     */
    public function testDestroy()
    {
        $name = $this->faker->word;

        $user = User::factory()->create();
        $user->createToken($name);

        $token = $user->tokens()->first();

        $this->actingAs($user)
            ->delete("/tokens/{$token->id}")
            ->assertRedirect();

        $this->assertDatabaseMissing('personal_access_tokens', [
            'name' => $name
        ]);
    }
}
