<?php

namespace Tests\Feature\Http\Controllers;

use App\Events\Published;
use App\Models\Blog;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testReturnsIndexViewForListOfPost(): void
    {
        $blog = Blog::factory()->create();

        $this->actingAs($blog->user)
            ->get(route('blogs.posts.index', $blog))
            ->assertOk()
            ->assertViewIs('blogs.posts.index');
    }

    public function testReturnsCreateViewForPost(): void
    {
        $blog = Blog::factory()->create();

        $this->actingAs($blog->user)
            ->get(route('blogs.posts.create', $blog))
            ->assertOk()
            ->assertViewIs('blogs.posts.create');
    }

    public function testCreatePostForBlog(): void
    {
        Event::fake();
        Storage::fake('public');

        $attachment = UploadedFile::fake()->image('file.jpg');

        $blog = Blog::factory()->hasSubscribers()->create();

        $data = [
            'title' => $this->faker->text(50),
            'content' => $this->faker->text(),
        ];

        $this->actingAs($blog->user)
            ->post(route('blogs.posts.store', $blog), [
                ...$data,
                'attachments' => [
                    $attachment,
                ],
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('posts', $data);

        $this->assertDatabaseHas('attachments', [
            'original_name' => $attachment->getClientOriginalName(),
            'name' => $attachment->hashName('attachments'),
        ]);

        Storage::disk('public')->assertExists(
            $attachment->hashName('attachments')
        );

        Event::assertDispatched(Published::class);
    }

    public function testReturnsShowViewForPost(): void
    {
        $post = Post::factory()->create();

        $this->actingAs($post->blog->user)
            ->get(route('posts.show', $post))
            ->assertOk()
            ->assertViewIs('blogs.posts.show');
    }

    public function testReturnsEditViewForPost(): void
    {
        $post = Post::factory()->create();

        $this->actingAs($post->blog->user)
            ->get(route('posts.edit', $post))
            ->assertOk()
            ->assertViewIs('blogs.posts.edit');
    }

    public function testUpdatePost(): void
    {
        Storage::fake('public');

        $attachment = UploadedFile::fake()->image('file.jpg');

        $post = Post::factory()->create();

        $data = [
            'title' => $this->faker->text(50),
            'content' => $this->faker->text(),
        ];

        $this->actingAs($post->blog->user)
            ->put(route('posts.update', $post), [
                ...$data,
                'attachments' => [
                    $attachment,
                ],
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('posts', $data);

        $this->assertDatabaseHas('attachments', [
            'original_name' => $attachment->getClientOriginalName(),
            'name' => $attachment->hashName('attachments'),
        ]);

        Storage::disk('public')->assertExists(
            $attachment->hashName('attachments')
        );
    }

    public function testDeletePost(): void
    {
        $post = Post::factory()->create();

        $this->actingAs($post->blog->user)
            ->delete(route('posts.destroy', $post))
            ->assertRedirect();

        $this->assertDatabaseMissing('posts', [
            'id' => $post->id,
        ]);
    }
}
