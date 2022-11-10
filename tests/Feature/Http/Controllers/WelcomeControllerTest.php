<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\WelcomeController;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class WelcomeControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 피드 테스트
     *
     * @return void
     */
    public function testWelcome()
    {
        $user = $this->user();

        $this->actingAs($user)
            ->get(action(WelcomeController::class))
            ->assertOk()
            ->assertViewIs('welcome');
    }

    /**
     * 피드 테스트 (구독)
     *
     * @return void
     */
    public function testWelcomeWithSubscriptions()
    {
        $subscriptions = $this->blog();
        $user = $this->user($subscriptions);

        $this->actingAs($user)
            ->get(action(WelcomeController::class))
            ->assertOk()
            ->assertViewIs('welcome');
    }

    /**
     * User
     *
     * @param \App\Models\Blog|\Illuminate\Support\Collection|null $subscriptions
     * @return \Illuminate\Database\Eloquent\Collection|\App\Models\User
     */
    private function user(Blog|Collection $subscriptions = null)
    {
        $factory = User::factory();

        if ($subscriptions) {
            $factory = $factory->hasAttached(
                factory: $subscriptions,
                relationship: 'subscriptions'
            );
        }

        return $factory->create();
    }

    /**
     * Blog
     *
     * @return \App\Models\Blog
     */
    private function blog()
    {
        $factory = Blog::factory()
            ->forUser()
            ->hasPosts(5);

        return $factory->create();
    }
}
