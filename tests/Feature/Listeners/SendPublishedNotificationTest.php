<?php

namespace Tests\Feature\Listeners;

use App\Events\Published;
use App\Listeners\SendPublishedNotification;
use App\Models\Blog;
use App\Models\Post;
use App\Models\User;
use App\Notifications\Published as PublishedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SendPublishedNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function testPublishedNotificationSentToSubscribers()
    {
        Notification::fake();

        $subscribers = User::factory(10)->create();
        $post = Post::factory()->for(Blog::factory()->forUser())->create();

        $event = new Published($subscribers, $post);

        $listener = new SendPublishedNotification();
        $listener->handle($event);

        Notification::assertSentTo(
            $event->subscribers,
            PublishedNotification::class
        );
    }
}
