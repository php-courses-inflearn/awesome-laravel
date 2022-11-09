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

    /**
     * 구독 이메일 테스트
     *
     * @return void
     */
    public function testSubscribed()
    {
        $user = $this->user();
        $blog = $this->blog();

        $mailable = new Subscribed($user, $blog);

        $mailable->assertSeeInOrderInHtml([
            $user->name,
            $blog->display_name,
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
