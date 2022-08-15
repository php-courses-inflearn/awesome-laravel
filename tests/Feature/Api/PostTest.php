<?php

namespace Tests\Feature\Api;

use App\Enums\TokenAbility;
use App\Models\Blog;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class PostTest extends TestCase
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
        $token = $this->token($blog, TokenAbility::POST_READ);

        $this->withToken($token)
            ->getJson("/api/blogs/{$blog->name}/posts")
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
        Storage::fake('public');

        $attachment = UploadedFile::fake()->image('file.jpg');

        $blog = $this->blog();
        $token = $this->token($blog, TokenAbility::POST_CREATE);

        $data = [
            'title' => $this->faker->text(50),
            'content' => $this->faker->text
        ];

        $this->withToken($token)
            ->postJson("/api/blogs/{$blog->name}/posts", $data + [
                'attachments' => [
                    $attachment
                ]
            ])
            ->assertCreated()
            ->assertJson(function (AssertableJson $json) use ($data) {
                $json->has('data', function (AssertableJson $json) use ($data) {
                    $json->whereAll($data)
                        ->hasAll(['id', 'title', 'content'])
                        ->etc();
                });
            });

        Storage::disk('public')->assertExists('attachments/' . $attachment->hashName());
    }

    /**
     * 글 상세페이지 테스트
     *
     * @return void
     */
    public function testShow()
    {
        $blog = $this->blog();
        $token = $this->token($blog, TokenAbility::POST_READ);

        $blog->posts->each(function (Post $post) use ($token) {
            $response = $this->withToken($token)
                ->getJson("/api/posts/{$post->id}")
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
            $this->withToken($token)
                ->getJson("/api/posts/{$post->id}", [
                    'If-None-Match' => $etag
                ])
                ->assertStatus(304);
        });
    }

    /**
     * 글 수정 테스트
     *
     * @return void
     */
    public function testUpdate()
    {
        $blog = $this->blog();
        $token = $this->token($blog, TokenAbility::POST_UPDATE);

        $blog->posts->each(function (Post $post) use ($token) {
            $data = [
                'title' => $this->faker->text(50),
                'content' => $this->faker->text
            ];

            $this->withToken($token)
                ->putJson("/api/posts/{$post->id}", $data)
                ->assertNoContent();
        });
    }

    /**
     * 글 삭제 테스트
     *
     * @return void
     */
    public function testDestroy()
    {
        $blog = $this->blog();
        $token = $this->token($blog, TokenAbility::POST_DELETE);

        $blog->posts->each(function (Post $post) use ($token) {
            $this->withToken($token)
                ->deleteJson("/api/posts/{$post->id}")
                ->assertNoContent();
        });
    }

    /**
     * @return mixed
     */
    private function blog()
    {
        $factory = Blog::factory()
            ->forUser()
            ->hasPosts(3);

        return $factory->create();
    }

    /**
     * @param Blog $blog
     * @param TokenAbility $ability
     *
     * @return string
     */
    private function token(Blog $blog, TokenAbility $ability)
    {
        $token = $blog->user->createToken(
            $this->faker->word, [$ability->value]
        );

        return $token->plainTextToken;
    }
}
