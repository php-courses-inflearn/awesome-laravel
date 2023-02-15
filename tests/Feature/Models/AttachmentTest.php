<?php

namespace Tests\Feature\Models;

use App\Models\Attachment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AttachmentTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testPruningAssociatedUploadedFile(): void
    {
        $storage = Storage::fake('public');

        $file = UploadedFile::fake()->image('avatar.jpg');
        $file->store('/', 'public');

        $attachment = Attachment::factory()->state([
            'original_name' => $file->getClientOriginalName(),
            'name' => $file->hashName(),
        ])->create();

        $storage->assertExists($attachment->name);

        $this->artisan('model:prune', [
            '--model' => [Attachment::class],
        ])->assertSuccessful();

        $this->assertDatabaseMissing('attachments', [
            'id' => $attachment->id,
        ]);

        $storage->assertMissing($attachment->name);
    }
}
