<?php

namespace Tests\Feature\Casts;

use App\Castables\Link as LinkCastable;
use App\Casts\Link;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
//        $link = new Link();
//
//        $attributes = [
//            'name' => $this->faker->imageUrl,
//        ];
//
//        $linkCastable = $link->get(new class extends Model
//        {
//        }, '', null, $attributes);
//
//        $this->assertInstanceOf(LinkCastable::class, $linkCastable);
//        $this->assertEquals($attributes['name'], $linkCastable->path);

        $attributes = [
            'name' => $this->faker->imageUrl,
        ];

        $model = $this->model($attributes);

        $this->assertInstanceOf(LinkCastable::class, $model->link);
        $this->assertEquals($attributes['name'], $model->link->path);
    }

    /**
     * Link 캐스트 접근자 테스트 (FilePath)
     *
     * @return void
     */
    public function testLinkWithFilePath()
    {
//        $link = new Link();
//
//        $attributes = [
//            'name' => $this->faker->filePath(),
//        ];
//
//        $linkCastable = $link->get(new class extends Model
//        {
//        }, '', null, $attributes);
//
//        $this->assertEquals(
//            Storage::disk('public')->url($attributes['name']),
//            $linkCastable->path
//        );
//
//        $this->assertInstanceOf(LinkCastable::class, $linkCastable);

        $attributes = [
            'name' => $this->faker->filePath(),
        ];

        $model = $this->model($attributes);

        $this->assertEquals(
            Storage::disk('public')->url($attributes['name']),
            $model->link->path
        );

        $this->assertInstanceOf(LinkCastable::class, $model->link);
    }

    /**
     * Link 캐스트 변이자 테스트
     *
     * @return void
     */
    public function testLinkSetCastable()
    {
//        $link = new Link();
//        $linkCastable = new LinkCastable(
//            $this->faker->imageUrl
//        );
//
//        $attributes = $link->set(new class extends Model
//        {
//        }, '', $linkCastable, []);
//
//        $this->assertEquals($attributes['name'], $linkCastable->path);

        $model = $this->model();
        $linkCastable = new LinkCastable(
            $this->faker->imageUrl
        );

        $model->link = $linkCastable;

        $this->assertInstanceOf(LinkCastable::class, $model->link);
        $this->assertEquals($linkCastable->path, $model->link->path);
    }

    /**
     * Link 캐스트 변이자 테스트 (Null)
     *
     * @return void
     */
    public function testLinkSetNull()
    {
//        $link = new Link();
//
//        $this->expectException(Exception::class);
//
//        $link->set(new class extends Model
//        {
//        }, '', null, []);

        $model = $this->model();

        $this->expectException(Exception::class);

        $model->link = null;
    }

    /**
     * Model
     *
     * @param $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    private function model($attributes = [])
    {
        return new class($attributes) extends Model
        {
            /**
             * The attributes that are mass assignable.
             *
             * @var array<string>
             */
            protected $fillable = [
                'name',
            ];

            /**
             * The attributes that should be cast.
             *
             * @var array<string, string>
             */
            protected $casts = [
                'link' => LinkCastable::class,
            ];
        };
    }
}
