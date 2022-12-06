<?php

namespace Tests\Feature\Http\Controllers;

use App\Events\Subscribed;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class SubscribeControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 구독 테스트
     *
     * @return void
     */
    public function testStore()
    {
        //Mail::fake();
        //Notification::fake();
        Event::fake();

        $user = $this->user();
        $blog = $this->blog();

        $this->actingAs($user)
            ->post(route('subscribe'), [
                'blog_id' => $blog->id,
            ])
            ->assertRedirect();

        $this->assertCount(1, $user->subscriptions);
        $this->assertCount(1, $blog->subscribers);

        $this->assertDatabaseHas('blog_user', [
            'user_id' => $user->id,
            'blog_id' => $blog->id,
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
    public function testDestroy()
    {
        $user = $this->user();
        $blog = $this->blog($user);

        $this->actingAs($user)
            ->post(route('unsubscribe'), [
                'blog_id' => $blog->id,
            ])
            ->assertRedirect();

        $this->assertDatabaseMissing('blog_user', [
            'user_id' => $user->id,
            'blog_id' => $blog->id,
        ]);
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

    /**
     * Blog
     *
     * @param  \App\Models\User|\Illuminate\Support\Collection|null  $subscribers
     * @return \App\Models\Blog
     */
    private function blog(User|Collection $subscribers = null)
    {
        $factory = Blog::factory()->forUser();

        if ($subscribers) {
            $factory = $factory->hasAttached(
                factory: $subscribers,
                relationship: 'subscribers'
            );
        }

        return $factory->create();
    }
}
