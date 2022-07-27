<?php

namespace Tests\Feature\Dashboard;

use App\Http\Middleware\RequirePassword;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubscribeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 내 구독자 테스트
     *
     * @return void
     */
    public function testSubscribers()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->withoutMiddleware(RequirePassword::class)
            ->get('/dashboard/subscribers')
            ->assertViewIs('dashboard.subscribers');
    }

    /**
     * 내가 구독한 블로그
     *
     * @return void
     */
    public function testSubscriptions()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->withoutMiddleware(RequirePassword::class)
            ->get('/dashboard/subscriptions')
            ->assertViewIs('dashboard.subscriptions');
    }
}
