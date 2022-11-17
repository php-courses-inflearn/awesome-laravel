<?php

namespace Tests\Feature\Observers;

use App\Models\Blog;
use App\Models\Comment;
use App\Models\Post;
use App\Observers\PostObserver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostObserverTest extends TestCase
{
    use RefreshDatabase;

    /**
     * PostObserver::deleting
     *
     * @return void
     */
    public function testDeleted()
    {
        $post = $this->article();
        $observer = new PostObserver();

        $this->assertDatabaseCount('comments', 1);

        //$post->delete();
        $observer->deleted($post);

        $this->assertCount(0, $post->comments);
        $this->assertDatabaseCount('comments', 0);
    }

    /**
     * Article
     *
     * @return \App\Models\Post
     */
    private function article()
    {
        $factory = Post::factory()->for(Blog::factory()->forUser())
            ->has(Comment::factory()->forUser());

        return $factory->create();
    }
}
