<?php

namespace Tests\Unit\Models;

use App\Models\Blog;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var int $count
     */
    private int $count = 3;

    /**
     * @var Carbon $now
     */
    private Carbon $now;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->now = now();
    }

    /**
     * User::feed() 테스트
     *
     * @return void
     */
    public function testFeed()
    {
        $feed = $this->user()->feed($this->count);

        foreach ($feed->chunk($this->count)->all() as $seconds => $posts) {
            $now = clone $this->now;
            $expected = $now->addSeconds($this->count - $seconds)->toDateTimeString();

            foreach ($posts as $post) {
                $this->assertEquals(
                    $expected,
                    $post->created_at->toDateTimeString()
                );
            }
        }
    }

    /**
     * User
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed
     */
    private function user()
    {
        $factory = User::factory();

        foreach (range(0, $this->count - 1) as $_) {
            $factory = $factory->hasAttached(
                factory: $this->blog(),
                relationship: 'subscriptions'
            );
        }

        return $factory->create();
    }

    /**
     * Blog
     *
     * @return mixed
     */
    private function blog()
    {
        $now = clone $this->now;

        $factory = Blog::factory()
            ->forUser()
            ->has(
                Post::factory($this->count)->state(
                    fn () => ['created_at' => $now->addSecond()],
                )
            );

        return $factory->create();
    }
}
