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

    /**
     * 검색 테스트
     *
     * @return void
     */
    public function testSearch()
    {
        $post = $this->article();
        $user = $this->user();

        $query = $post->title;

        $this->actingAs($user)
            ->get(route('search', [
                'query' => $query,
            ]))
            ->assertOk()
            ->assertViewIs('search')
            ->assertSeeText($query);
    }

    /**
     * Article
     *
     * @return \App\Models\Post
     */
    private function article()
    {
        $factory = Post::factory()->for(
            Blog::factory()->forUser()
        );

        return $factory->create();
    }

    /**
     * User
     *
     * @return \App\Models\User
     */
    private function user()
    {
        $factory = User::factory();

        return $factory->create();
    }
}
