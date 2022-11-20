<?php

namespace Tests\Feature\Models\Scopes;

use App\Models\Scopes\VerifiedScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
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

        $scope->apply($queryBuilder, new class extends Model
        {
        });

        $this->assertTrue(Str::containsAll(
            $queryBuilder->toSql(),
            ['where', 'email_verified_at', 'is not null']
        ));
    }
}
