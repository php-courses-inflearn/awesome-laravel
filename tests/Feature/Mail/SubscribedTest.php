<?php

namespace Tests\Feature\Mail;

use App\Mail\Subscribed;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscribedTest extends TestCase
{
    use RefreshDatabase;

    public function testDisplaysUserNameAndBlogDisplayName(): void
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create();

        $mailable = new Subscribed($user, $blog);

        $mailable->assertHasSubject(
            '[라라벨] 구독 알림'
        );

        $mailable->assertSeeInOrderInHtml([
            $user->name,
            $blog->display_name,
        ]);
    }
}
