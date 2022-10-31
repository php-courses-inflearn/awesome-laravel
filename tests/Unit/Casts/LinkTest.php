<?php

namespace Tests\Unit\Casts;

use App\Castables\Link;
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

    /**
     * Link 캐스트 접근자 테스트
     *
     * @return void
     */
    public function testLink()
    {
        $attachment = $this->attachment();

        $this->assertEquals(1, $attachment->external);
        $this->assertInstanceOf(Link::class, $attachment->link);
    }

    /**
     * Link 캐스트 접근자 테스트 (UploadedFile)
     *
     * @return void
     */
    public function testLinkWithUploadedFile()
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('file.jpg');

        $attachment = $this->attachment($file);
        $this->assertInstanceOf(Link::class, $attachment->link);

        $this->assertEquals('/storage/'.$file->hashName(), $attachment->link->path);
    }

    /**
     * Link 캐스트 변이자 테스트
     *
     * @return void
     */
    public function testLinkSetCastable()
    {
        $attachment = $this->attachment();

        $url = $this->faker->imageUrl;
        $attachment->link = new Link($url);

        $attachment->save();

        $this->assertEquals($url, $attachment->link->path);
        $this->assertInstanceOf(Link::class, $attachment->link);
    }

    /**
     * Link 캐스트 변이자 테스트 (Null)
     *
     * @return void
     */
    public function testLinkSetNull()
    {
        $attachment = $this->attachment();

        $this->expectException(Exception::class);
        $attachment->link = null;
    }

    /**
     * Attachment
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed
     */
    public function attachment(UploadedFile $attachment = null)
    {
        $factory = Attachment::factory();

        if ($attachment) {
            $factory = $factory->state([
                'original_name' => $attachment->getClientOriginalName(),
                'name' => $attachment->hashName(),
            ]);
        }

        return $factory->create();
    }
}
