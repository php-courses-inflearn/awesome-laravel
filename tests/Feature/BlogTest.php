<?php

namespace Tests\Feature;

use App\Http\Middleware\Authenticate;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class BlogTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * 블로그 목록 테스트
     *
     * @return void
     */
    public function testIndex()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get('/blogs');

        $response->assertViewIs('blogs.index');
    }

    /**
     * 블로그 생성 폼 테스트
     *
     * @return void
     */
    public function testCreate()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get('/blogs/create');

        $response->assertViewIs('blogs.create');
    }

    /**
     * 블로그 생성 테스트
     *
     * @return void
     */
    public function testStore()
    {
        $user = User::factory()->create();

        $data = [
            'name' => $this->faker->userName,
            'display_name' => $this->faker->words(3, true)
        ];

        $response = $this->actingAs($user)
            ->post('/blogs', $data);

        $this->assertDatabaseHas('blogs', $data);

        $response->assertRedirect();
    }

    /**
     * 블로그 상세페이지 테스트
     *
     * @return void
     */
    public function testShow()
    {
        $blog = Blog::factory()->forUser()->create();

        $response = $this->actingAs($blog->user)
            ->get("/blogs/{$blog->name}");

        $response->assertViewIs('blogs.show');
    }

    /**
     * 블로그 수정 폼 테스트
     *
     * @return void
     */
    public function testEdit()
    {
        $blog = Blog::factory()->forUser()->create();

        $response = $this->actingAs($blog->user)
            ->get("/blogs/{$blog->name}/edit");

        $response->assertViewIs('blogs.edit');
    }

    /**
     * 블로그 수정 테스트
     *
     * @return void
     */
    public function testUpdate()
    {
        $blog = Blog::factory()->forUser()->create();

        $data = [
            'name' => $this->faker->userName,
            'display_name' => $this->faker->unique()->words(3, true)
        ];

        $response = $this->actingAs($blog->user)
            ->put("/blogs/{$blog->name}", $data);

        $this->assertDatabaseHas('blogs', $data);

        $response->assertRedirect();
    }

    /**
     * 블로그 삭제 테스트
     *
     * @return void
     */
    public function testDestroy()
    {
        $blog = Blog::factory()->forUser()->create();

        $response = $this->actingAs($blog->user)
            ->delete("/blogs/{$blog->name}");

        $this->assertDatabaseMissing('blogs', [
            'name' => $blog->name
        ]);

        $response->assertRedirect();
    }
}
