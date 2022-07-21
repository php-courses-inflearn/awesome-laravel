<?php

namespace Tests\Feature;

use App\Events\Published;
use App\Models\Blog;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * 글 목록 테스트
     *
     * @return void
     */
    public function testIndex()
    {
        $user = User::factory()->hasBlogs(3)->create();

        foreach ($user->blogs as $blog) {
            $response = $this->actingAs($user)
                ->get("/blogs/{$blog->name}/posts");

            $response->assertViewIs('blogs.posts.index');
        }
    }

    /**
     * 글 생성 폼 테스트
     *
     * @return void
     */
    public function testCreate()
    {
        $user = User::factory()->hasBlogs(3)->create();

        foreach ($user->blogs as $blog) {
            $response = $this->actingAs($user)
                ->get("/blogs/{$blog->name}/posts/create");

            $response->assertViewIs('blogs.posts.create');
        }
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

        $user = User::factory()->hasBlogs(3)->create();

        foreach ($user->blogs as $blog) {
            $data = [
                'title' => $this->faker->text(50),
                'content' => $this->faker->text
            ];

            $this->actingAs($user)
                ->post("/blogs/{$blog->name}/posts", $data + [
                    'attachments' => [
                        $attachment
                    ]
                ]);

            $this->assertDatabaseHas('posts', $data);

            $this->assertDatabaseHas('attachments', [
                'original_name' => $attachment->getClientOriginalName(),
                'name' => $attachment->hashName()
            ]);

            Storage::disk('public')->assertExists('attachments/' . $attachment->hashName());

            Event::assertDispatched(Published::class);
        }
    }

    /**
     * 글 상세페이지 테스트
     *
     * @return void
     */
    public function testShow()
    {
        $post = Post::factory()->for(Blog::factory()->forUser())->create();

        $response = $this->actingAs($post->blog->user)
            ->get("/posts/{$post->id}");

        $response->assertViewIs('blogs.posts.show');
    }

    /**
     * 글 수정 폼 테스트
     *
     * @return void
     */
    public function testEdit()
    {
        $post = Post::factory()->for(Blog::factory()->forUser())->create();

        $response = $this->actingAs($post->blog->user)
            ->get("/posts/{$post->id}/edit");

        $response->assertViewIs('blogs.posts.edit');
    }

    /**
     * 글 수정 테스트
     *
     * @return void
     */
    public function testUpdate()
    {
        $post = Post::factory()->for(Blog::factory()->forUser())->create();

        $data = [
            'title' => $this->faker->text(50),
            'content' => $this->faker->text
        ];

        $response = $this->actingAs($post->blog->user)
            ->put("/posts/{$post->id}", $data);

        $this->assertDatabaseHas('posts', $data);

        $response->assertRedirect();
    }

    /**
     * 글 삭제 테스트
     *
     * @return void
     */
    public function testDestroy()
    {
        $post = Post::factory()->for(Blog::factory()->forUser())->create();

        $response = $this->actingAs($post->blog->user)
            ->delete("/posts/{$post->id}");

        $this->assertDatabaseMissing('posts', [
            'id' => $post->id
        ]);

        $response->assertRedirect();
    }
}
