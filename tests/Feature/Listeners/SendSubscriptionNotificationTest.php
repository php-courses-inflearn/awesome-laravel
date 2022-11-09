<?php

namespace Tests\Feature\Listeners;

use App\Events\Subscribed;
use App\Listeners\SendSubscriptionNotification;
use App\Models\Blog;
use App\Models\User;
use App\Notifications\Subscribed as SubscribedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SendSubscriptionNotificationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 구독 이벤트 리스너 테스트
     *
     * @return void
     */
    public function testSendSubscriptionNotification()
    {
        Notification::fake();

        $user = $this->user();
        $blog = $this->blog();

        $event = new Subscribed($user, $blog);

        $listener = new SendSubscriptionNotification();
        $listener->handle($event);

        Notification::assertSentTo(
            $event->blog->user,
            SubscribedNotification::class
        );
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
     * @return \App\Models\Blog
     */
    private function blog()
    {
        $factory = Blog::factory()->forUser();

        return $factory->create();
    }
}
