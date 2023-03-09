<?php

namespace Tests\Feature\Mail;

use App\Mail\Advertisement;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdvertisementTest extends TestCase
{
    use RefreshDatabase;

    public function testDisplaysListOfPostTitles(): void
    {
        $posts = Post::factory(5)->create();

        $mailable = new Advertisement($posts);

        $mailable->assertHasSubject(
            '[라라벨] 라라벨 커뮤니티의 최신글 살펴보기'
        );

        $mailable->assertSeeInOrderInHtml(
            $posts->pluck('title')->toArray()
        );
    }
}
