<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Blog;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SearchControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testReturnsSearchViewWithSearchQueryInQueryString()
    {
        $user = User::factory()->create();
        $post = Post::factory()->for(Blog::factory()->forUser())->create();

        $query = $post->title;

        $this->actingAs($user)
            ->get(route('search', [
                'query' => $query,
            ]))
            ->assertOk()
            ->assertViewIs('search')
            ->assertViewHas('posts', fn (Collection $posts) => $posts->contains($post))
            ->assertViewHas('query', $query);
    }

    public function testSearchView()
    {
        $posts = Post::factory(5)->for(Blog::factory()->forUser())->create();
        $query = $this->faker->word();

        $view = $this->withViewErrors([])
            ->view('search', [
                'posts' => $posts,
                'query' => $query,
            ]);

        $view->assertViewHas('query', $query);
        $view->assertViewHas('posts', $posts);
    }
}
