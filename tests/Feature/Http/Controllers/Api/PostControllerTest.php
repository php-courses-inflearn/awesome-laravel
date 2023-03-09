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

    public function testRequestListOfPost(): void
    {
        $blog = Blog::factory()->hasPosts(3)->create();

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

    public function testCreatePostAndReturnsItself(): void
    {
        Event::fake();
        Storage::fake('public');

        $attachment = UploadedFile::fake()->image('file.jpg');

        $blog = Blog::factory()->hasPosts(3)->hasSubscribers()->create();

        $data = [
            'title' => $this->faker->text(50),
            'content' => $this->faker->text(),
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

    public function testRequestPost(): void
    {
        $post = Post::factory()->create();

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

        $this->getJson(route('api.posts.show', $post), [
            'If-None-Match' => $etag,
        ])
        ->assertStatus(304);
    }

    public function testUpdatePost(): void
    {
        Storage::fake('public');

        $attachment = UploadedFile::fake()->image('avatar.jpg');

        $post = Post::factory()->create();

        $data = [
            'title' => $this->faker->text(50),
            'content' => $this->faker->text(),
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

    public function testDeletePost(): void
    {
        $post = Post::factory()->create();

        Sanctum::actingAs($post->blog->user, [
            Ability::POST_DELETE->value,
        ]);

        $this->deleteJson(route('api.posts.destroy', $post))
            ->assertNoContent();

        $this->assertDatabaseMissing('posts', [
            'id' => $post->id,
        ]);
    }
}
