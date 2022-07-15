<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubscribeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 구독 테스트
     *
     * @return void
     */
    public function testSubscribe()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->forUser()->create();

        $response = $this->actingAs($user)
            ->post("/subscribe/{$blog->name}");

        $this->assertDatabaseHas('blog_user', [
            'user_id' => $user->id,
            'blog_id' => $blog->id
        ]);

        $response->assertRedirect();
    }

    /**
     * 구독취소 테스트
     *
     * @return void
     */
    public function testUnsubscribe()
    {
        $user = User::factory()->create();

        $blog = Blog::factory()
            ->forUser()
            ->hasAttached(factory: $user, relationship: 'subscribers')
            ->create();

        $response = $this->actingAs($user)
            ->delete("/unsubscribe/{$blog->name}");

        $this->assertDatabaseMissing('blog_user', [
            'user_id' => $user->id,
            'blog_id' => $blog->id
        ]);

        $response->assertRedirect();
    }
}
