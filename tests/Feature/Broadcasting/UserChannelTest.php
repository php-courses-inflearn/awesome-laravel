<?php

namespace Tests\Feature\Broadcasting;

use App\Broadcasting\UserChannel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserChannelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * UserChannel 테스트
     *
     * @return void
     */
    public function testUserChannel()
    {
        $user = $this->user();

        $userChannel = new UserChannel();

        $this->assertTrue(
            $userChannel->join($user, $user->id)
        );
        $this->assertNotTrue(
            $userChannel->join($user, 2)
        );
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
}
