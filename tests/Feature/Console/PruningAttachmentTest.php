<?php

namespace Tests\Feature\Console;

use App\Models\Attachment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PruningAttachmentTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Attachment Pruning 테스트
     *
     * @return void
     */
    public function testPruningAttachment()
    {
        $storage = Storage::fake('public');

        $file = UploadedFile::fake()->image('file.jpg');
        $file->store('/', 'public');

        $attachment = $this->attachment($file);

        $storage->assertExists($attachment->name);

        $this->artisan('model:prune', [
            '--model' => [Attachment::class]
        ])->assertSuccessful();

        $storage->assertMissing($attachment->name);
    }

    /**
     * @param  \Illuminate\Http\UploadedFile  $attachment
     * @return \App\Models\Attachment
     */
    public function attachment(UploadedFile $attachment)
    {
        $factory = Attachment::factory()
            ->state([
                'original_name' => $attachment->getClientOriginalName(),
                'name' => $attachment->hashName(),
            ]);

        return $factory->create();
    }
}
