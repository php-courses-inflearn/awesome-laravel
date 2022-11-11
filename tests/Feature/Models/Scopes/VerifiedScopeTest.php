<?php

namespace Tests\Feature\Models\Scopes;

use App\Models\Scopes\VerifiedScope;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Tests\TestCase;

class VerifiedScopeTest extends TestCase
{
    /**
     * VerifiedScope 테스트
     *
     * @return void
     */
    public function testApply()
    {
        $scope = new VerifiedScope();
        $queryBuilder = app(Builder::class);
        $model = User::factory()->create();

        $scope->apply($queryBuilder, $model);

        $this->assertStringContainsString(
            'where "email_verified_at" is not null',
            $queryBuilder->toSql()
        );
    }
}
