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
        $blog = $this->blog();

        foreach ($blog->posts as $post) {
            $data = [
                'content' => $this->faker->text
            ];

            $user = $this->user();

            $this->actingAs($user)
                ->post("/posts/{$post->id}/comments", $data)
                ->assertRedirect();

            $this->assertDatabaseHas('comments', $data + [
                'commentable_type' => Post::class, 'commentable_id' => $post->id
            ]);
        }
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
            'content' => $this->faker->text
        ];

        $this->actingAs($user)
            ->post("/posts/{$comment->commentable->id}/comments", [
                'parent_id' => $comment->id, 'content' => $data['content']
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('comments', $data + [
            'commentable_type' => Post::class, 'commentable_id' => $comment->commentable->id
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
        $comment = $this->comment();

        $this->actingAs($comment->user)
            ->delete("/comments/{$comment->id}")
            ->assertRedirect();

        $this->assertDatabaseHas('comments', [
            'commentable_type' => Post::class, 'commentable_id' => $comment->commentable->id
        ]);
    }

    /**
     * User
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Factories\HasFactory|\Illuminate\Database\Eloquent\Model|mixed
     */
    private function blog()
    {
        $factory = Blog::factory()
            ->forUser()
            ->hasPosts(3);

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
