<?php

namespace Tests\Feature\Notifications;

use App\Models\Post;
use App\Models\User;
use App\Notifications\Published;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\Messages\MailMessage;
use Tests\TestCase;

class PublishedTest extends TestCase
{
    use RefreshDatabase;

    public function testToMailContainsExpectedSubjectAndContent(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $notification = new Published($post);

        $mailMessage = $notification->toMail($user);
        $this->assertInstanceOf(MailMessage::class, $mailMessage);

        $this->assertStringContainsString(
            $notification->post->blog->display_name,
            $mailMessage->subject
        );
        $this->assertStringContainsString(
            $notification->post->title,
            $mailMessage->subject
        );
        $this->assertContains(
            substr($notification->post->content, 0, 200),
            $mailMessage->introLines
        );
        $this->assertStringContainsString(
            route('posts.show', $notification->post),
            $mailMessage->actionUrl
        );
    }

    public function testToBroadcastContainsPost(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $notification = new Published($post);
        $broadcastMessage = $notification->toBroadcast($user);

        $this->assertContains($notification->post, $broadcastMessage->data);
    }
}
