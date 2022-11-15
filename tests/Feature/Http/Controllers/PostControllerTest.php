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

    /**
     * 글 목록 테스트
     *
     * @return void
     */
    public function testIndex()
    {
        $blog = $this->blog();

        $this->actingAs($blog->user)
            ->get(route('blogs.posts.index', [
                'blog' => $blog->name,
            ]))
            ->assertOk()
            ->assertViewIs('blogs.posts.index');
    }

    /**
     * 글 생성 폼 테스트
     *
     * @return void
     */
    public function testCreate()
    {
        $blog = $this->blog();

        $this->actingAs($blog->user)
            ->get(route('blogs.posts.create', [
                'blog' => $blog->name,
            ]))
            ->assertOk()
            ->assertViewIs('blogs.posts.create');
    }

    /**
     * 글 생성 테스트
     *
     * @return void
     */
    public function testStore()
    {
        Event::fake();

        Storage::fake('public');

        $attachment = UploadedFile::fake()->image('file.jpg');

        $blog = $this->blog();

        $data = [
            'title' => $this->faker->text(50),
            'content' => $this->faker->text,
        ];

        $this->actingAs($blog->user)
            ->post(route('blogs.posts.store', [
                'blog' => $blog->name,
            ]), [
                ...$data,
                'attachments' => [
                    $attachment,
                ],
            ])
            ->assertRedirect();

        $this->assertCount(1, $blog->posts);
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

    /**
     * 글 상세페이지 테스트
     *
     * @return void
     */
    public function testShow()
    {
        $post = $this->article();

        $this->actingAs($post->blog->user)
            ->get(route('posts.show', [
                'post' => $post->id,
            ]))
            ->assertOk()
            ->assertViewIs('blogs.posts.show');
    }

    /**
     * 글 수정 폼 테스트
     *
     * @return void
     */
    public function testEdit()
    {
        $post = $this->article();

        $this->actingAs($post->blog->user)
            ->get(route('posts.edit', [
                'post' => $post->id,
            ]))
            ->assertViewIs('blogs.posts.edit');
    }

    /**
     * 글 수정 테스트
     *
     * @return void
     */
    public function testUpdate()
    {
        Storage::fake('public');

        $attachment = UploadedFile::fake()->image('file.jpg');

        $post = $this->article();

        $data = [
            'title' => $this->faker->text(50),
            'content' => $this->faker->text,
        ];

        $this->actingAs($post->blog->user)
            ->put(route('posts.update', [
                'post' => $post->id,
            ]), [
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

    /**
     * 글 삭제 테스트
     *
     * @return void
     */
    public function testDestroy()
    {
        $post = $this->article();

        $this->actingAs($post->blog->user)
            ->delete(route('posts.destroy', [
                'post' => $post->id,
            ]))
            ->assertRedirect();

        $this->assertDatabaseMissing('posts', [
            'id' => $post->id,
        ]);
    }

    /**
     * Blog
     *
     * @return \App\Models\Blog
     */
    private function blog()
    {
        $factory = Blog::factory()
            ->forUser()
            ->hasSubscribers();

        return $factory->create();
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
}
