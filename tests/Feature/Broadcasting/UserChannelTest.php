<?php

namespace Tests\Feature\Broadcasting;

use App\Broadcasting\UserChannel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserChannelTest extends TestCase
{
    use RefreshDatabase;

    public function testJoinMethodGrantsAccessToChannelForAuthenticatedUser(): void
    {
        $user = User::factory()->create();

        $userChannel = new UserChannel();

        $this->assertTrue(
            $userChannel->join($user, $user->id)
        );
    }
}
