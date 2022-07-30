<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testSearch()
    {
        $post = Post::factory()
            ->for(Blog::factory()->forUser())
            ->create();

        $query = $post->title;

        $this->get("/search?query={$query}")
            ->assertOk()
            ->assertViewIs('search')
            ->assertSeeText($post->title);
    }
}
