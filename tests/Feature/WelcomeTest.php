<?php

namespace Tests\Feature;

use App\Http\Middleware\RequirePassword;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WelcomeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 피드 테스트
     *
     * @return void
     */
    public function testWelome()
    {
        $this->get('/')
            ->assertViewIs('welcome');

        $user = User::factory()->hasAttached(
            factory: Blog::factory(2)
                ->forUser()
                ->hasPosts(5),
            relationship: 'subscriptions'
        )->create();

        $this->actingAs($user)
            ->get('/')
            ->assertViewIs('welcome');
    }
}
