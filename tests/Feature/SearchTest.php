<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testSearch()
    {
        $post = $this->article();
        $user = $this->user();

        $query = $post->title;

        $this->actingAs($user)
            ->get("/search?query={$query}")
            ->assertOk()
            ->assertViewIs('search')
            ->assertSeeText($post->title);
    }

    /**
     * Article
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Factories\HasFactory|\Illuminate\Database\Eloquent\Model|mixed
     */
    private function article()
    {
        $factory = Post::factory()
            ->for(Blog::factory()->forUser());

        return $factory->create();
    }

    /**
     * User
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Factories\HasFactory|\Illuminate\Database\Eloquent\Model|mixed
     */
    private function user()
    {
        $factory = User::factory();

        return $factory->create();
    }
}
