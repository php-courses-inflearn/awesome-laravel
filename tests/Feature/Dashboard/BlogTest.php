<?php

namespace Tests\Feature\Dashboard;

use App\Http\Middleware\RequirePassword;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BlogTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 블로그 대시보드 테스트
     *
     * @return void
     */
    public function testDashboard()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->withoutMiddleware(RequirePassword::class)
            ->get('/dashboard/blogs')
            ->assertViewIs('dashboard.blogs');
    }
}
