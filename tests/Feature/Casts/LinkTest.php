<?php

namespace Tests\Feature\Casts;

use App\Castables\Link as LinkCastable;
use App\Models\Attachment;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LinkTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testLinkAccessorWithExternalPath()
    {
        $attachment = Attachment::factory()->state([
            'name' => $this->faker->imageUrl,
        ])->create();

        $this->assertEquals($attachment->name, $attachment->link->path);
    }

    public function testLinkAccessorWithFilePath()
    {
        $attachment = UploadedFile::fake()->image('avatar.jpg');

        $attachment = Attachment::factory()->state([
            'original_name' => $attachment->getClientOriginalName(),
            'name' => $attachment->hashName(),
        ])->create();

        $this->assertEquals(
            Storage::disk('public')->url($attachment->name),
            $attachment->link->path
        );
    }

    public function testLinkMutatorSetsCastable()
    {
        $attachment = Attachment::factory()->create();

        $linkCastable = new LinkCastable(
            $this->faker->imageUrl
        );

        $attachment->link = $linkCastable;

        $this->assertEquals($linkCastable->path, $attachment->link->path);
    }

    public function testLinkMutatorThrowsExceptionOnInvalidValue()
    {
        $attachment = Attachment::factory()->create();

        $this->expectException(Exception::class);

        $attachment->link = null;
    }
}
