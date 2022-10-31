<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * 댓글 생성 테스트
     *
     * @return void
     */
    public function testStore()
    {
        $post = $this->article();
        $user = $this->user();

        $data = [
            'content' => $this->faker->text,
        ];

        $this->actingAs($user)
            ->post("/posts/{$post->id}/comments", $data)
            ->assertRedirect();

        $this->assertDatabaseHas('comments', $data + [
            'commentable_type' => Post::class, 'commentable_id' => $post->id,
        ]);
    }

    /**
     * 자식 댓글 생성 테스트
     *
     * @return void
     */
    public function testStoreChildComment()
    {
        $comment = $this->comment();
        $user = $this->user();

        $data = [
            'parent_id' => $comment->id,
            'content' => $this->faker->text,
        ];

        $this->actingAs($user)
            ->post("/posts/{$comment->commentable->id}/comments", $data)
            ->assertRedirect();

        $this->assertDatabaseHas('comments', $data + [
            'commentable_type' => Post::class, 'commentable_id' => $comment->commentable->id,
        ]);
    }

    /**
     * 댓글 수정 테스트
     *
     * @return void
     */
    public function testUpdate()
    {
        $comment = $this->comment();

        $data = [
            'content' => $this->faker->text,
        ];

        $this->actingAs($comment->user)
            ->put("/comments/{$comment->id}", $data)
            ->assertRedirect();

        $this->assertDatabaseHas('comments', $data + [
            'id' => $comment->id,
            'commentable_type' => Post::class,
            'commentable_id' => $comment->commentable->id,
        ]);
    }

    /**
     * 댓글 삭제 테스트
     *
     * @return void
     */
    public function testDestroy()
    {
        $comment = $this->comment();

        $this->actingAs($comment->user)
            ->delete("/comments/{$comment->id}")
            ->assertRedirect();

        $this->assertSoftDeleted('comments', [
            'id' => $comment->id,
            'commentable_type' => Post::class,
            'commentable_id' => $comment->commentable->id,
        ]);
    }

    /**
     * User
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

    /**
     * Comment
     *
     * @return mixed
     */
    private function comment()
    {
        $factory = Comment::factory()
            ->forUser()
            ->for(
                Post::factory()->for(
                    Blog::factory()->forUser()
                ),
                'commentable'
            );

        return $factory->create();
    }
}
