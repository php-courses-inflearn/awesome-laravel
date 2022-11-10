<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Attachment;
use App\Models\Blog;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AttachmentControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * 파일 업로드 테스트
     *
     * @return void
     */
    public function testStore()
    {
        Storage::fake('public');

        $attachment = UploadedFile::fake()->image('file.jpg');

        $post = $this->article();

        $this->actingAs($post->blog->user)
            ->post(
                route('posts.attachments.store', [
                    'post' => $post->id,
                ]),
                [
                    'attachments' => [
                        $attachment,
                    ],
                ]
            )
            ->assertSuccessful();

        $this->assertCount(1, $post->attachments);

        $this->assertDatabaseHas('attachments', [
            'original_name' => $attachment->getClientOriginalName(),
            'name' => $attachment->hashName('attachments'),
        ]);

        Storage::disk('public')->assertExists(
            $attachment->hashName('attachments')
        );
    }

    /**
     * 파일 삭제 테스트
     *
     * @return void
     */
    public function testDestroy()
    {
        Storage::fake('public');

        $attachment = UploadedFile::fake()->image('file.jpg');

        $post = $this->article($attachment);

        foreach ($post->attachments as $attachment) {
            $this->actingAs($post->blog->user)
                ->delete(route('attachments.destroy', [
                    'attachment' => $attachment->id,
                ]))
                ->assertRedirect();

            $this->assertDatabaseMissing('attachments', [
                'id' => $attachment->id,
            ]);
        }
    }

    /**
     * Article
     *
     * @param  \Illuminate\Http\UploadedFile|null  $attachment
     * @return Post
     */
    private function article(UploadedFile $attachment = null)
    {
        $factory = Post::factory()
            ->for(
                Blog::factory()->forUser()
            );

        if ($attachment) {
            $factory = $factory->has(
                Attachment::factory()
                    ->state([
                        'original_name' => $attachment->getClientOriginalName(),
                        'name' => $attachment->hashName('attachments'),
                    ])
            );
        }

        return $factory->create();
    }
}
