<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Blog;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchControllerTest extends TestCase
{
    use RefreshDatabase;

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
            ->assertSeeText($query);
    }
}
