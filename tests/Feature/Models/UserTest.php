<?php

namespace Tests\Feature\Models;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function testVerifiedScope(): void
    {
        $user = User::factory()->create();
        $unverifiedUser = User::factory()->unverified()->create();

        $users = User::verified()->get();

        $this->assertCount(1, $users);

        $this->assertTrue(
            $users->contains($user)
        );

        $this->assertFalse(
            $users->contains($unverifiedUser)
        );
    }
}
