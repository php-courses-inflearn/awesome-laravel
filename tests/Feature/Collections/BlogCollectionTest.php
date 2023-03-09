<?php

namespace Tests\Feature\Collections;

use App\Collections\BlogCollection;
use App\Models\Blog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogCollectionTest extends TestCase
{
    use RefreshDatabase;

    public function testReturnsLatestListOfPost(): void
    {
        $blogs = Blog::factory(3)->hasPosts(3)->create();

        $blogCollection = new BlogCollection($blogs);
        $feed = $blogCollection->feed();

        $this->assertCount(9, $feed);

        $this->assertEquals(
            $blogs->flatMap->posts->sortByDesc('created_at'), $feed
        );
    }
}
