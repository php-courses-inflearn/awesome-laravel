<?php

namespace Tests\Feature;

use App\Models\Attachment;
use App\Models\Blog;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AttachmentTest extends TestCase
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

        $post = Post::factory()->for(Blog::factory()->forUser())->create();

        $this->actingAs($post->blog->user)
            ->post("/posts/{$post->id}/attachments", [
                'attachments' => [
                    $attachment
                ]
            ])
            ->assertSuccessful();

        $this->assertDatabaseHas('attachments', [
            'original_name' => $attachment->getClientOriginalName(),
            'name' => $attachment->hashName()
        ]);

        Storage::disk('public')->assertExists('attachments/'. $attachment->hashName());
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

        $post = Post::factory()
            ->for(Blog::factory()->forUser())
            ->has(
                Attachment::factory()
                    ->state([
                        'original_name' => $attachment->getClientOriginalName(),
                        'name' => $attachment->hashName()
                    ])
            )
            ->create();

        $id = $post->attachments()->first()->id;

        $this->actingAs($post->blog->user)
            ->delete("/attachments/{$id}")
            ->assertRedirect();

        $this->assertDatabaseMissing('attachments', [
            'id' => $id
        ]);
    }
}
