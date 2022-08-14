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
        $user = $this->user();

        $user->blogs->each(function (Blog $blog) use ($user) {
            $this->actingAs($user)
                ->get("/blogs/{$blog->name}/posts")
                ->assertOk()
                ->assertViewIs('blogs.posts.index');
        });
    }

    /**
     * 글 생성 폼 테스트
     *
     * @return void
     */
    public function testCreate()
    {
        $user = $this->user();

        $user->blogs->each(function (Blog $blog) use ($user) {
            $this->actingAs($user)
                ->get("/blogs/{$blog->name}/posts/create")
                ->assertOk()
                ->assertViewIs('blogs.posts.create');
        });
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

        $user = $this->user();

        $user->blogs->each(function (Blog $blog) use ($user, $attachment) {
            $data = [
                'title' => $this->faker->text(50),
                'content' => $this->faker->text
            ];

            $this->actingAs($user)
                ->post("/blogs/{$blog->name}/posts", $data + [
                    'attachments' => [
                        $attachment
                    ]
                ])
                ->assertRedirect();

            $this->assertDatabaseHas('posts', $data);

            $this->assertDatabaseHas('attachments', [
                'original_name' => $attachment->getClientOriginalName(),
                'name' => $attachment->hashName()
            ]);

            Storage::disk('public')->assertExists('attachments/' . $attachment->hashName());

            Event::assertDispatched(Published::class);
        });
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
            ->get("/posts/{$post->id}")
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
            ->get("/posts/{$post->id}/edit")
            ->assertViewIs('blogs.posts.edit');
    }

    /**
     * 글 수정 테스트
     *
     * @return void
     */
    public function testUpdate()
    {
        $post = $this->article();

        $data = [
            'title' => $this->faker->text(50),
            'content' => $this->faker->text
        ];

        $this->actingAs($post->blog->user)
            ->put("/posts/{$post->id}", $data)
            ->assertRedirect();

        $this->assertDatabaseHas('posts', $data);
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
            ->delete("/posts/{$post->id}")
            ->assertRedirect();

        $this->assertDatabaseMissing('posts', [
            'id' => $post->id
        ]);
    }

    /**
     * User
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Factories\HasFactory|\Illuminate\Database\Eloquent\Model|mixed
     */
    private function user()
    {
        $factory = User::factory()->hasBlogs(3);

        return $factory->create();
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
}
