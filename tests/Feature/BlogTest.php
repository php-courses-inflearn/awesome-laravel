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
        $user = $this->user();

        $this->actingAs($user)
            ->get('/blogs')
            ->assertViewIs('blogs.index');
    }

    /**
     * 블로그 생성 폼 테스트
     *
     * @return void
     */
    public function testCreate()
    {
        $user = $this->user();

        $this->actingAs($user)
            ->get('/blogs/create')
            ->assertViewIs('blogs.create');
    }

    /**
     * 블로그 생성 테스트
     *
     * @return void
     */
    public function testStore()
    {
        $user = $this->user();

        $data = [
            'name' => $this->faker->userName,
            'display_name' => $this->faker->words(3, true)
        ];

        $this->actingAs($user)
            ->post('/blogs', $data)
            ->assertRedirect();

        $this->assertDatabaseHas('blogs', $data);
    }

    /**
     * 블로그 상세페이지 테스트
     *
     * @return void
     */
    public function testShow()
    {
        $user = $this->user();
        $blog = $this->blog();

        $this->actingAs($user)
            ->get("/blogs/{$blog->name}")
            ->assertViewIs('blogs.show');
    }

    /**
     * 블로그 수정 폼 테스트
     *
     * @return void
     */
    public function testEdit()
    {
        $blog = $this->blog();

        $this->actingAs($blog->user)
            ->get("/blogs/{$blog->name}/edit")
            ->assertViewIs('blogs.edit');
    }

    /**
     * 블로그 수정 테스트
     *
     * @return void
     */
    public function testUpdate()
    {
        $blog = $this->blog();

        $data = [
            'name' => $this->faker->userName,
            'display_name' => $this->faker->unique()->words(3, true)
        ];

        $this->actingAs($blog->user)
            ->put("/blogs/{$blog->name}", $data)
            ->assertRedirect();

        $this->assertDatabaseHas('blogs', $data);
    }

    /**
     * 블로그 삭제 테스트
     *
     * @return void
     */
    public function testDestroy()
    {
        $blog = $this->blog();

        $this->actingAs($blog->user)
            ->delete("/blogs/{$blog->name}")
            ->assertRedirect();

        $this->assertDatabaseMissing('blogs', [
            'name' => $blog->name
        ]);
    }

    /**
     * User
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Factories\HasFactory|\Illuminate\Database\Eloquent\Model|mixed
     */
    private function user()
    {
        $factory = User::factory();

        return $factory->create();
    }

    /**
     * Blog
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Factories\HasFactory|\Illuminate\Database\Eloquent\Model|mixed
     */
    private function blog()
    {
        $factory = Blog::factory()->forUser();

        return $factory->create();
    }
}
