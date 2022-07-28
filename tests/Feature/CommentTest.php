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
        $user = User::factory()->has(Blog::factory()->hasPosts(3))->create();

        $data = [
            'content' => $this->faker->text
        ];

        foreach ($user->blogs()->first()->posts as $post) {
            /**
             * 부모 댓글 생성
             */
            $this->actingAs($user)
                ->post("/posts/{$post->id}/comments", $data)
                ->assertRedirect();

            $this->assertDatabaseHas('comments', $data + [
                'commentable_type' => Post::class, 'commentable_id' => $post->id
            ]);

            /**
             * 자식 댓글 생성
             */
            $this->actingAs($user)
                ->post("/posts/{$post->id}/comments", [
                    'parent_id' => $post->comments()->first()->id,
                    'content' => $this->faker->text,
                ])
                ->assertRedirect();

            $this->assertDatabaseHas('comments', $data + [
                'commentable_type' => Post::class, 'commentable_id' => $post->id
            ]);
        }
    }

    /**
     * 댓글 수정 테스트
     *
     * @return void
     */
    public function testUpdate()
    {
        $comment = Comment::factory()
            ->forUser()
            ->for(
                Post::factory()->for(
                    Blog::factory()->forUser()
                ), 'commentable')
            ->create();

        $data = [
            'content' => $this->faker->text
        ];

        $this->actingAs($comment->user)
            ->put("/comments/{$comment->id}", $data)
            ->assertRedirect();

        $this->assertDatabaseHas('comments', $data + [
            'commentable_type' => Post::class, 'commentable_id' => $comment->commentable->id
        ]);
    }

    /**
     * 댓글 삭제 테스트
     *
     * @return void
     */
    public function testDestroy()
    {
        $comment = Comment::factory()
            ->forUser()
            ->for(
                Post::factory()->for(
                    Blog::factory()->forUser()
                ), 'commentable')
            ->create();

        $this->actingAs($comment->user)
            ->delete("/comments/{$comment->id}")
            ->assertRedirect();

        $this->assertDatabaseHas('comments', [
            'commentable_type' => Post::class, 'commentable_id' => $comment->commentable->id
        ]);
    }
}