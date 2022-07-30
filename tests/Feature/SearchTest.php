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
        $data = [
            'title' => $this->faker->text(50),
            'content' => $this->faker->text
        ];

        $post = Post::factory()
            ->for(Blog::factory()->forUser())
            ->create($data);

        $query = $post->title;

        $this->get("/search?query={$query}")
            ->assertOk()
            ->assertViewIs('search')
            ->assertSeeText($post->title);
    }
}
