<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Enums\TokenAbility;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TokenControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * 토큰 생성 폼 테스트
     *
     * @return void
     */
    public function testCreate()
    {
        $user = $this->user();

        $this->actingAs($user)
            ->get(route('tokens.create'))
            ->assertOk()
            ->assertViewIs('tokens.create');
    }

    /**
     * 토큰 생성 테스트
     *
     * @return void
     */
    public function testStore()
    {
        $user = $this->user();

        $abilities = $this->faker->randomElements(
            collect(TokenAbility::cases())->pluck('value')->toArray()
        );

        $name = $this->faker->word;

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

    /**
     * 토큰 삭제 테스트
     *
     * @return void
     */
    public function testDestroy()
    {
        $user = $this->user();

        $name = $this->faker->word;
        $user->createToken($name);

        $token = $user->tokens()->first();

        $this->actingAs($user)
            ->delete(route('tokens.destroy', [
                'token' => $token->id,
            ]))
            ->assertRedirect();

        $this->assertDatabaseMissing('personal_access_tokens', [
            'name' => $name,
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
