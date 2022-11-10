<?php

namespace Tests\Feature\Http\Controllers\Dashboard;

use App\Http\Middleware\RequirePassword;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscribeControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 내 구독자 테스트
     *
     * @return void
     */
    public function testIndex()
    {
        $user = $this->user();

        $this->actingAs($user)
            ->withoutMiddleware(RequirePassword::class)
            ->get(route('dashboard.subscribers'))
            ->assertOk()
            ->assertViewIs('dashboard.subscribers');
    }

    /**
     * 내가 구독한 블로그
     *
     * @return void
     */
    public function testSubscriptions()
    {
        $user = $this->user();

        $this->actingAs($user)
            ->withoutMiddleware(RequirePassword::class)
            ->get(route('dashboard.subscriptions'))
            ->assertOk()
            ->assertViewIs('dashboard.subscriptions');
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
