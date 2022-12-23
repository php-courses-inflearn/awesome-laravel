<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Enums\Ability;
use App\Events\Published;
use App\Models\Blog;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * 글 목록 테스트
     *
     * @return void
     */
    public function testIndex()
    {
        $blog = $this->blog();

        Sanctum::actingAs($blog->user, [
            Ability::POST_READ->value,
        ]);

        $this->getJson(route('api.blogs.posts.index', $blog))
            ->assertOk()
            ->assertJson(function (AssertableJson $json) {
                $json->whereType('data', 'array')
                    ->has('data', 3, function (AssertableJson $json) {
                        $json->hasAll(['id', 'title', 'content'])->etc();
                    });
            });
    }

    /**
     * 글 생성 테스트
     *
     * @return void
     */
    public function testStore()
    {
        Event::fake();
        Storage::fake('public');

        $attachment = UploadedFile::fake()->image('file.jpg');
        $blog = $this->blog();

        $data = [
            'title' => $this->faker->text(50),
            'content' => $this->faker->text,
        ];

        Sanctum::actingAs($blog->user, [
            Ability::POST_CREATE->value,
        ]);

        $this->postJson(route('api.blogs.posts.store', $blog), [
            ...$data,
            'attachments' => [
                $attachment,
            ],
        ])
        ->assertCreated()
        ->assertJson(function (AssertableJson $json) use ($data) {
            $json->has('data', function (AssertableJson $json) use ($data) {
                $json->whereAll($data)
                    ->hasAll(['id', 'title', 'content'])
                    ->etc();
            });
        });

        $this->assertDatabaseHas('posts', $data);

        $this->assertDatabaseHas('attachments', [
            'original_name' => $attachment->getClientOriginalName(),
            'name' => $attachment->hashName('attachments'),
        ]);

        Storage::disk('public')->assertExists(
            $attachment->hashName('attachments')
        );

        Event::assertDispatched(Published::class);
    }

    /**
     * 글 상세페이지 테스트
     *
     * @return void
     */
    public function testShow()
    {
        $post = $this->article();

        Sanctum::actingAs($post->blog->user, [
            Ability::POST_READ->value,
        ]);

        $response = $this->getJson(route('api.posts.show', $post))
            ->assertOk()
            ->assertJson(function (AssertableJson $json) {
                $json->has('data', function (AssertableJson $json) {
                    $json->hasAll(['id', 'title', 'content'])
                        ->etc();
                });
            })
            ->assertHeader('Etag');

        $etag = $response->getEtag();

        /**
         * Etag
         */
        $this->getJson(route('api.posts.show', $post), [
            'If-None-Match' => $etag,
        ])
        ->assertStatus(304);
    }

    /**
     * 글 수정 테스트
     *
     * @return void
     */
    public function testUpdate()
    {
        Storage::fake('public');

        $attachment = UploadedFile::fake()->image('file.jpg');
        $post = $this->article();

        $data = [
            'title' => $this->faker->text(50),
            'content' => $this->faker->text,
        ];

        Sanctum::actingAs($post->blog->user, [
            Ability::POST_UPDATE->value,
        ]);

        $this->putJson(route('api.posts.update', $post), [
            ...$data,
            'attachments' => [
                $attachment,
            ],
        ])
        ->assertNoContent();

        $this->assertDatabaseHas('posts', $data);

        $this->assertDatabaseHas('attachments', [
            'original_name' => $attachment->getClientOriginalName(),
            'name' => $attachment->hashName('attachments'),
        ]);

        Storage::disk('public')->assertExists(
            $attachment->hashName('attachments')
        );
    }

    /**
     * 글 삭제 테스트
     *
     * @return void
     */
    public function testDestroy()
    {
        $post = $this->article();

        Sanctum::actingAs($post->blog->user, [
            Ability::POST_DELETE->value,
        ]);

        $this->deleteJson(route('api.posts.destroy', $post))
            ->assertNoContent();

        $this->assertDatabaseMissing('posts', [
            'id' => $post->id,
        ]);
    }

    /**
     * Blog
     *
     * @return \App\Models\Blog
     */
    private function blog()
    {
        $factory = Blog::factory()
            ->forUser()
            ->hasPosts(3)
            ->hasSubscribers();

        return $factory->create();
    }

    /**
     * Article
     *
     * @return \App\Models\Post
     */
    private function article()
    {
        $factory = Post::factory()->for(
            Blog::factory()->forUser()
        );

        return $factory->create();
    }
}
