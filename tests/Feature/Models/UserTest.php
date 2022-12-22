<?php

namespace Tests\Feature\Models;

use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * 로컬 VerifiedScope 테스트
     *
     * @return void
     */
    public function testVerifiedScope()
    {
        $user = new User();

        $this->assertTrue(Str::containsAll(
            $user->verified()->toSql(),
            ['where', 'email_verified_at', 'is not null']
        ));
    }
}
