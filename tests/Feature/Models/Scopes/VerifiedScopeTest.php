<?php

namespace Tests\Feature\Models\Scopes;

use App\Models\Scopes\VerifiedScope;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Tests\TestCase;

class VerifiedScopeTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testApply()
    {
        $scope = new VerifiedScope();
        $queryBuilder = app(Builder::class);

        $scope->apply($queryBuilder, User::factory()->create());

        $this->assertStringContainsString(
            'where "email_verified_at" is not null',
            $queryBuilder->toSql()
        );
    }
}
