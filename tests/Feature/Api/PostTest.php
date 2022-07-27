<?php

namespace Tests\Feature\Api;

use App\Enums\TokenAbility;
use App\Models\Blog;
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
        [$user, $token] = $this->userWithToken(TokenAbility::POST_READ);

        $this->withToken($token)
            ->getJson("/api/blogs/{$user->blogs()->first()->name}/posts")
            ->assertOk()
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll([
                    'current_page',
                    'data',
                    'first_page_url',
                    'from',
                    'last_page',
                    'last_page_url',
                    'links',
                    'next_page_url',
                    'path',
                    'per_page',
                    'prev_page_url',
                    'to',
                    'total'
                ])
                ->whereAllType([
                    'data' => 'array', 'links' => 'array'
                ])
                ->has('data', 3, function (AssertableJson $json) {
                    $json->hasAll(['id', 'title', 'content', 'blog_id'])->etc();
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

        [$user, $token] = $this->userWithToken(TokenAbility::POST_CREATE);

        $data = [
            'title' => $this->faker->text(50),
            'content' => $this->faker->text
        ];

        $this->withToken($token)
            ->postJson("/api/blogs/{$user->blogs()->first()->name}/posts", $data + [
                'attachments' => [
                    $attachment
                ]
            ])
            ->assertCreated()
            ->assertJson(function (AssertableJson $json) use ($data) {
                $json->whereAll($data)
                    ->hasAll(['id', 'title', 'content', 'blog_id'])
                    ->etc();
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
        [$user, $token] = $this->userWithToken(TokenAbility::POST_READ);

        foreach ($user->blogs()->first()->posts as $post) {
            $this->withToken($token)
                ->getJson("/api/posts/{$post->id}")
                ->assertOk()
                ->assertJson(function (AssertableJson $json) {
                    $json->hasAll(['id', 'title', 'content', 'blog_id'])->etc();
                });
        }
    }

    /**
     * 글 수정 테스트
     *
     * @return void
     */
    public function testUpdate()
    {
        [$user, $token] = $this->userWithToken(TokenAbility::POST_UPDATE);

        foreach ($user->blogs()->first()->posts as $post) {
            $data = [
                'title' => $this->faker->text(50),
                'content' => $this->faker->text
            ];

            $this->withToken($token)
                ->putJson("/api/posts/{$post->id}", $data)
                ->assertNoContent();
        }
    }

    /**
     * 글 삭제 테스트
     *
     * @return void
     */
    public function testDestroy()
    {
        [$user, $token] = $this->userWithToken(TokenAbility::POST_DELETE);

        foreach ($user->blogs()->first()->posts as $post) {
            $this->withToken($token)
                ->deleteJson("/api/posts/{$post->id}")
                ->assertNoContent();
        }
    }

    /**
     * @param TokenAbility $ability
     * @return array
     */
    private function userWithToken(TokenAbility $ability)
    {
        $user = User::factory()
            ->has(Blog::factory()->hasPosts(3))
            ->create();

        $token = $user->createToken(
            $this->faker->word, [$ability->value]
        );

        return [$user, $token->plainTextToken];
    }
}
