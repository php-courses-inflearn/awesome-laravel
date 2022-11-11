<?php

namespace Tests\Feature\Notifications;

use App\Models\Blog;
use App\Models\Post;
use App\Models\User;
use App\Notifications\Published;
use DragonCode\Support\Facades\Helpers\Str;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Tests\TestCase;

class PublishedTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Published 알림 테스트
     *
     * @return void
     */
    public function testPublished()
    {
        $user = $this->user();
        $post = $this->article();

        $notification = new Published($post);

        $this->toMail($notification, $notification->toMail($user));
        $this->toBroadcast($notification, $notification->toBroadcast($user));
        $this->viaQueues($notification->viaQueues());
    }

    /**
     * @param  \App\Notifications\Published  $notification
     * @param  \Illuminate\Notifications\Messages\MailMessage|\Illuminate\Mail\Mailable  $mailMessage
     * @return void
     */
    private function toMail(Published $notification, MailMessage|Mailable $mailMessage)
    {
        $this->assertStringContainsString(
            $notification->post->blog->display_name,
            $mailMessage->subject
        );
        $this->assertStringContainsString(
            $notification->post->title,
            $mailMessage->subject
        );
        $this->assertContains(
            Str::substr($notification->post->content, 0, 200),
            $mailMessage->introLines
        );
        $this->assertStringContainsString(
            route('posts.show', $notification->post->id),
            $mailMessage->actionUrl
        );
    }

    /**
     * @param  \App\Notifications\Published  $notification
     * @param  \Illuminate\Notifications\Messages\BroadcastMessage  $broadcastMessage
     * @return void
     */
    private function toBroadcast(Published $notification, BroadcastMessage $broadcastMessage)
    {
        $this->assertContains($notification->post, $broadcastMessage->data);
    }

    /**
     * @param  array<string, string>  $queues
     * @return void
     */
    private function viaQueues(array $queues)
    {
        $this->assertEquals($queues, [
            'broadcast' => 'broadcasts',
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
