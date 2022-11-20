<?php

namespace Tests\Feature\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * 글로벌 VerifiedScope 테스트
     *
     * @return void
     */
//    public function testGlobalVerifiedScope()
//    {
//        $this->assertStringContainsString(
//            "select * from `users` where `email_verified_at` is not null",
//            User::toSql()
//        );
//    }

    /**
     * 로컬 VerifiedScope 테스트
     *
     * @return void
     */
    public function testVerifiedScope()
    {
        $user = new User();
        $queryBuilder = app(Builder::class);

        $user->scopeVerified($queryBuilder);

        $this->assertStringContainsString(
            'where "email_verified_at" is not null',
            $queryBuilder->toSql()
        );

        //, or
        $this->assertEquals(
            'select * from `users` where `email_verified_at` is not null',
            $user->verified()->toSql()
        );
    }
}
