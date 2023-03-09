<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testCreateCommentForPost(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $data = [
            'content' => $this->faker->text(),
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

    public function testCreateChildCommentForComment(): void
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create();

        $data = [
            'content' => $this->faker->text(),
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

    public function testUpdateComment(): void
    {
        $comment = Comment::factory()->create();

        $data = [
            'content' => $this->faker->text(),
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

    public function testDeleteComment(): void
    {
        $comment = Comment::factory()->create();

        $this->actingAs($comment->user)
            ->delete(route('comments.destroy', $comment))
            ->assertRedirect();

        $this->assertSoftDeleted('comments', [
            'id' => $comment->id,
            'commentable_type' => Post::class,
            'commentable_id' => $comment->commentable->id,
        ]);
    }
}
