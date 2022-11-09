<?php

namespace Tests\Feature\Notifications;

use App\Models\Blog;
use App\Models\User;
use App\Notifications\Subscribed;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Tests\TestCase;

class SubscribedTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 구독 알림 테스트
     *
     * @return void
     */
    public function testSubscribed()
    {
        $user = $this->user();
        $blog = $this->blog();

        $notification = new Subscribed($user, $blog);

        $this->toMail($notification, $notification->toMail($user));
        $this->toMail($notification, $notification->toMail(new AnonymousNotifiable()));
        $this->toBroadcast($notification, $notification->toBroadcast($user));
        $this->viaQueues($notification->viaQueues());
    }

    /**
     * @param  \App\Notifications\Subscribed  $notification
     * @param  \Illuminate\Notifications\Messages\MailMessage|\Illuminate\Mail\Mailable  $mailable
     * @return void
     */
    private function toMail(Subscribed $notification, MailMessage|Mailable $mailable)
    {
        $mailable->assertSeeInOrderInHtml([
            $notification->user->name,
            $notification->blog->display_name,
        ]);
    }

    /**
     * @param  \App\Notifications\Subscribed  $notification
     * @param  \Illuminate\Notifications\Messages\BroadcastMessage  $broadcastMessage
     * @return void
     */
    private function toBroadcast(Subscribed $notification, BroadcastMessage $broadcastMessage)
    {
        $this->assertContains($notification->user, $broadcastMessage->data);
    }

    /**
     * @param  array<string, string>  $queues
     * @return void
     */
    private function viaQueues(array $queues)
    {
        $this->assertEquals($queues, [
            'mail' => 'emails',
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
