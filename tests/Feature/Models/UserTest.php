<?php

namespace Tests\Feature\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
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
//        $this->assertTrue(Str::containsAll(
//            User::toSql(),
//            ['where', 'email_verified_at', 'is not null']
//        ));
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

        $this->assertTrue(Str::containsAll(
            $queryBuilder->toSql(),
            ['where', 'email_verified_at', 'is not null']
        ));

        $this->assertTrue(Str::containsAll(
            $user->verified()->toSql(),
            ['where', 'email_verified_at', 'is not null']
        ));
    }
}
