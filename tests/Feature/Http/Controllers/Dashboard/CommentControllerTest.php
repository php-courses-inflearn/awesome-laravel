<?php

namespace Tests\Feature\Http\Controllers\Dashboard;

use App\Http\Middleware\RequirePassword;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 댓글 대시보드 테스트
     *
     * @return void
     */
    public function testIndex()
    {
        $user = $this->user();

        $this->actingAs($user)
            ->withoutMiddleware(RequirePassword::class)
            ->get(route('dashboard.comments'))
            ->assertViewIs('dashboard.comments');
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
