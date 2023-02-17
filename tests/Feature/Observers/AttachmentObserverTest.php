<?php

namespace Tests\Feature\Observers;

use App\Models\Attachment;
use App\Observers\AttachmentObserver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AttachmentObserverTest extends TestCase
{
    use RefreshDatabase;

    public function testDeletingUploadedFileOnAttachmentDeletion(): void
    {
        $storage = Storage::fake('public');

        $file = UploadedFile::fake()->image('avatar.jpg');
        $file->store('/', 'public');

        $attachment = Attachment::factory()->state([
            'original_name' => $file->getClientOriginalName(),
            'name' => $file->hashName(),
        ])->create();

        $observer = new AttachmentObserver();

        $observer->deleted($attachment);

        $storage->assertMissing($attachment->name);
    }
}
