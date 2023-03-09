<?php

namespace Tests\Feature\Listeners;

use App\Events\Subscribed;
use App\Listeners\SendSubscribedNotification;
use App\Models\Blog;
use App\Models\User;
use App\Notifications\Subscribed as SubscribedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SendSubscribedNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function testSubscribedNotificationSentToBlogOwner(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $blog = Blog::factory()->create();

        $event = new Subscribed($user, $blog);

        $listener = new SendSubscribedNotification();
        $listener->handle($event);

        Notification::assertSentTo(
            $event->blog->user,
            SubscribedNotification::class
        );
    }
}
