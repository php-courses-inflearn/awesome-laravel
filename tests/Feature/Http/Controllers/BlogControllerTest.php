<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BlogControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testReturnsIndexViewForListOfBlog(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('blogs.index'))
            ->assertOk()
            ->assertViewIs('blogs.index');
    }

    public function testReturnsCreateViewForBlog(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('blogs.create'))
            ->assertOk()
            ->assertViewIs('blogs.create');
    }

    public function testCreateBlog(): void
    {
        $user = User::factory()->create();

        $data = [
            'name' => $this->faker->userName(),
            'display_name' => $this->faker->words(3, true),
        ];

        $this->actingAs($user)
            ->post(route('blogs.store'), $data)
            ->assertRedirect();

        $this->assertCount(1, $user->blogs);
        $this->assertDatabaseHas('blogs', $data);
    }

    public function testReturnsShowViewForBlog(): void
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create();

        $this->actingAs($user)
            ->get(route('blogs.show', $blog))
            ->assertOk()
            ->assertViewIs('blogs.show');
    }

    public function testReturnsEditViewForBlog(): void
    {
        $blog = Blog::factory()->create();

        $this->actingAs($blog->user)
            ->get(route('blogs.edit', $blog))
            ->assertOk()
            ->assertViewIs('blogs.edit');
    }

    public function testUpdateBlog(): void
    {
        $blog = Blog::factory()->create();

        $data = [
            'name' => $this->faker->userName(),
            'display_name' => $this->faker->unique()->words(3, true),
        ];

        $this->actingAs($blog->user)
            ->put(route('blogs.update', $blog), $data)
            ->assertRedirect();

        $this->assertDatabaseHas('blogs', $data);
    }

    public function testDeleteBlog(): void
    {
        $blog = Blog::factory()->create();

        $this->actingAs($blog->user)
            ->delete(route('blogs.destroy', $blog))
            ->assertRedirect();

        $this->assertDatabaseMissing('blogs', [
            'name' => $blog->name,
        ]);
    }
}
