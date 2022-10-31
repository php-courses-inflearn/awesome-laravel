<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WelcomeTest extends TestCase
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
            ->get('/')
            ->assertOk()
            ->assertViewIs('welcome');
    }

    /**
     * User
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Factories\HasFactory|\Illuminate\Database\Eloquent\Model|mixed
     */
    private function user()
    {
        $factory = User::factory()->hasAttached(
            factory: Blog::factory(2)
                ->forUser()
                ->hasPosts(5),
            relationship: 'subscriptions'
        );

        return $factory->create();
    }
}
