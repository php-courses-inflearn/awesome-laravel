<?php

namespace Tests\Feature\Models\Scopes;

use App\Models\Scopes\VerifiedScope;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VerifiedScopeTest extends TestCase
{
    use RefreshDatabase;

    public function testVerifiedScope(): void
    {
        $user = User::factory()->create();
        $unverifiedUser = User::factory()->unverified()->create();

        User::addGlobalScope(new VerifiedScope());

        $users = User::all();

        $this->assertCount(1, $users);

        $this->assertTrue(
            $users->contains($user)
        );

        $this->assertFalse(
            $users->contains($unverifiedUser)
        );
    }
}
