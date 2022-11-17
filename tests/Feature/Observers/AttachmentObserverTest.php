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

    /**
     * AttachmentObserver::deleted 테스트
     *
     * @return void
     */
    public function testDeleted()
    {
        $storage = Storage::fake('public');

        $attachment = UploadedFile::fake()->image('file.jpg');
        $attachment->storePublicly('/', 'public');

        $attachment = $this->attachment($attachment);

        $observer = new AttachmentObserver();

        $storage->assertExists($attachment->name);

        //$attachment->delete();
        $observer->deleted($attachment);

        $storage->assertMissing($attachment->name);
    }

    /**
     * Attachment
     *
     * @param  \Illuminate\Http\UploadedFile  $attachment
     * @return \App\Models\Attachment
     */
    public function attachment(UploadedFile $attachment)
    {
        $factory = Attachment::factory()->state([
            'original_name' => $attachment->getClientOriginalName(),
            'name' => $attachment->hashName(),
        ]);

        return $factory->create();
    }
}
