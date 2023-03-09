<?php

namespace Tests\Feature\Notifications;

use App\Mail\Subscribed as SubscribedMailable;
use App\Models\Blog;
use App\Models\User;
use App\Notifications\Subscribed;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\AnonymousNotifiable;
use Tests\TestCase;

class SubscribedTest extends TestCase
{
    use RefreshDatabase;

    public function testToMailReturnsSubscribedMailable(): void
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create();

        $notification = new Subscribed($user, $blog);

        $this->assertInstanceOf(SubscribedMailable::class, $notification->toMail($user));
        $this->assertInstanceOf(SubscribedMailable::class, $notification->toMail(new AnonymousNotifiable()));
    }

    public function testToBroadcastContainsUser(): void
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create();

        $notification = new Subscribed($user, $blog);
        $broadcastMessage = $notification->toBroadcast($user);

        $this->assertContains($notification->user, $broadcastMessage->data);
    }
}
