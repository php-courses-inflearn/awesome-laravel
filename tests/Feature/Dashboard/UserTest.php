<?php

namespace Dashboard;

use App\Http\Middleware\RequirePassword;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 사용자 정보 폼 테스트
     *
     * @return void
     */
    public function testDashboard()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->withoutMiddleware(RequirePassword::class)
            ->get('/dashboard')
            ->assertViewIs('dashboard.user');
    }
}
