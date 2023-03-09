<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Attachment;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AttachmentControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testCreateAttachmentForPost(): void
    {
        Storage::fake('public');

        $attachment = UploadedFile::fake()->image('file.jpg');

        $post = Post::factory()->create();

        $this->actingAs($post->blog->user)
            ->post(route('posts.attachments.store', $post), [
                'attachments' => [
                    $attachment,
                ],
            ])
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

    public function testDeleteAttachmentFromPost(): void
    {
        Storage::fake('public');

        $attachment = UploadedFile::fake()->image('file.jpg');

        $post = Post::factory()->has(
            Attachment::factory()->state([
                'original_name' => $attachment->getClientOriginalName(),
                'name' => $attachment->hashName('attachments'),
            ])
        )->create();

        foreach ($post->attachments as $attachment) {
            $this->actingAs($post->blog->user)
                ->delete(route('attachments.destroy', $attachment))
                ->assertRedirect();

            $this->assertDatabaseMissing('attachments', [
                'id' => $attachment->id,
            ]);
        }
    }
}
