<?php

namespace Tests\Feature\Mail;

use App\Mail\Advertisement;
use App\Models\Blog;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdvertisementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Advertisement 이메일 테스트
     *
     * @return void
     */
    public function testAdvertisement()
    {
        $posts = $this->articles();

        $mailable = new Advertisement($posts);

        $mailable->assertHasSubject(
            '(광고) 라라벨 커뮤니티의 최신글 살펴보기!'
        );

        $mailable->assertSeeInOrderInHtml(
            $posts->pluck('title')->toArray()
        );
    }

    /**
     * Articles
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function articles()
    {
        $factory = Post::factory(5)->for(
            Blog::factory()->forUser()
        );

        return $factory->create();
    }
}
