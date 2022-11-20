<?php

namespace Tests\Feature\Listeners;

use App\Events\Published;
use App\Listeners\SendPublishingNotification;
use App\Models\Blog;
use App\Models\Post;
use App\Models\User;
use App\Notifications\Published as PublishedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SendPublishingNotificationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * SendPublishingNotification 이벤트 리스너 테스트
     *
     * @return void
     */
    public function testSendPublishingNotification()
    {
        Notification::fake();

        $subscribers = $this->subscribers();
        $post = $this->article();

        $event = new Published($subscribers, $post);

        $listener = new SendPublishingNotification();
        $listener->handle($event);
        //event($event);

        Notification::assertSentTo(
            $event->subscribers,
            PublishedNotification::class
        );
    }

    /**
     * Subscribers
     *
     * @param  User|Collection|null  $subscribers
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function subscribers()
    {
        $factory = User::factory(10);

        return $factory->create();
    }

    /**
     * Article
     *
     * @return \App\Models\Post
     */
    private function article()
    {
        $factory = Post::factory()->for(
            Blog::factory()->forUser()
        );

        return $factory->create();
    }
}
