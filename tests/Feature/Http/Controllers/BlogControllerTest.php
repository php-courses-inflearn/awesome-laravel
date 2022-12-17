<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BlogControllerTest extends TestCase
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
            ->get(route('blogs.index'))
            ->assertOk()
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
            ->get(route('blogs.create'))
            ->assertOk()
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
            'display_name' => $this->faker->words(3, true),
        ];

        $this->actingAs($user)
            ->post(route('blogs.store'), $data)
            ->assertRedirect();

        $this->assertCount(1, $user->blogs);
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
            ->get(route('blogs.show', $blog))
            ->assertOk()
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
            ->get(route('blogs.edit', $blog))
            ->assertOk()
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
            'display_name' => $this->faker->unique()->words(3, true),
        ];

        $this->actingAs($blog->user)
            ->put(route('blogs.update', $blog), $data)
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
            ->delete(route('blogs.destroy', $blog))
            ->assertRedirect();

        $this->assertDatabaseMissing('blogs', [
            'name' => $blog->name,
        ]);
    }

    /**
     * User
     *
     * @return \App\Models\User
     */
    private function user()
    {
        $factory = User::factory();

        return $factory->create();
    }

    /**
     * Blog
     *
     * @return \App\Models\Blog
     */
    private function blog()
    {
        $factory = Blog::factory()->forUser();

        return $factory->create();
    }
}
