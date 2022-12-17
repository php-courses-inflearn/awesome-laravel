<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Blog;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentControllerTest extends TestCase
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
            ->post(route('posts.comments.store', $post), $data)
            ->assertRedirect();

        $this->assertCount(1, $post->comments);
        $this->assertCount(1, $user->comments);

        $this->assertDatabaseHas('comments', [
            ...$data,
            'commentable_type' => Post::class,
            'commentable_id' => $post->id,
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
            'content' => $this->faker->text,
        ];

        $this->actingAs($user)
            ->post(route('posts.comments.store', $comment->commentable), [
                ...$data,
                'parent_id' => $comment->id,
            ])
            ->assertRedirect();

        $this->assertCount(1, $comment->replies);

        $this->assertDatabaseHas('comments', [
            ...$data,
            'parent_id' => $comment->id,
            'commentable_type' => Post::class,
            'commentable_id' => $comment->commentable->id,
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
            ->put(route('comments.update', $comment), $data)
            ->assertRedirect();

        $this->assertDatabaseHas('comments', [
            ...$data,
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
            ->delete(route('comments.destroy', $comment))
            ->assertRedirect();

        $this->assertSoftDeleted('comments', [
            'id' => $comment->id,
            'commentable_type' => Post::class,
            'commentable_id' => $comment->commentable->id,
        ]);
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

    /**
     * Comment
     *
     * @return \App\Models\Comment
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
