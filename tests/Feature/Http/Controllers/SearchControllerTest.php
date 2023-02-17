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

    public function testReturnsSearchViewWithSearchQueryInQueryString(): void
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
}
