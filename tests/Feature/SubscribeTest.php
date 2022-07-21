<?php

namespace Tests\Feature;

use App\Events\Subscribed;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use App\Mail\Subscribed as SubscribedMailable;
use App\Notifications\Subscribed as SubscribedNotification;

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
        //Mail::fake();
        //Notification::fake();
        Event::fake();

        $user = User::factory()->create();
        $blog = Blog::factory()->forUser()->create();

        $this->actingAs($user)
            ->post("/subscribe/{$blog->name}")
            ->assertRedirect();

        $this->assertDatabaseHas('blog_user', [
            'user_id' => $user->id,
            'blog_id' => $blog->id
        ]);

        Event::assertDispatched(Subscribed::class);
        //Notification::assertSentTo($blog->user, SubscribedNotification::class);
        //Mail::assertQueued(SubscribedMailable::class);
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

        $this->actingAs($user)
            ->delete("/unsubscribe/{$blog->name}")
            ->assertRedirect();

        $this->assertDatabaseMissing('blog_user', [
            'user_id' => $user->id,
            'blog_id' => $blog->id
        ]);
    }
}
