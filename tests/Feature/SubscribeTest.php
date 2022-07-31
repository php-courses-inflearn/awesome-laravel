<?php

namespace Tests\Feature;

use App\Events\Subscribed;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
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

        $user = $this->user();
        $blog = $this->blog();

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
        $user = $this->user();
        $blog = $this->blog($user);

        $this->actingAs($user)
            ->delete("/unsubscribe/{$blog->name}")
            ->assertRedirect();

        $this->assertDatabaseMissing('blog_user', [
            'user_id' => $user->id,
            'blog_id' => $blog->id
        ]);
    }

    /**
     * User
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Factories\HasFactory|\Illuminate\Database\Eloquent\Model|mixed
     */
    private function user()
    {
        $factory = User::factory();

        return $factory->create();
    }

    /**
     * Blog
     *
     * @param User|Collection|null $subscribers
     * @return mixed
     */
    private function blog(User|Collection $subscribers = null)
    {
        $factory = Blog::factory()->forUser();

        if ($subscribers) {
            $factory = $factory->hasAttached(
                factory: $subscribers, relationship: 'subscribers'
            );
        }

        return $factory->create();
    }
}
