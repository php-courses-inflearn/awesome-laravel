<?php

namespace Tests\Feature\Http\Controllers\Dashboard;

use App\Http\Middleware\RequirePassword;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TokenControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 토큰 대시보드 테스트
     *
     * @return void
     */
    public function testIndex()
    {
        $user = $this->user();

        $this->actingAs($user)
            ->withoutMiddleware(RequirePassword::class)
            ->get(route('dashboard.tokens'))
            ->assertViewIs('dashboard.tokens');
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
