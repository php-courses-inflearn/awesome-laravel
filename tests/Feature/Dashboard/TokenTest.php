<?php

namespace Tests\Feature\Dashboard;

use App\Http\Middleware\RequirePassword;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TokenTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 토큰 대시보드 테스트
     *
     * @return void
     */
    public function testDashboard()
    {
        $user = $this->user();

        $this->actingAs($user)
            ->withoutMiddleware(RequirePassword::class)
            ->get('/dashboard/tokens')
            ->assertViewIs('dashboard.tokens');
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
