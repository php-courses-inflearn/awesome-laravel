<?php

namespace Tests\Feature\Castables;

use App\Castables\Link;
use App\Casts\Link as LinkCast;
use Tests\TestCase;

class LinkTest extends TestCase
{
    /**
     * Link Castable 테스트
     *
     * @return void
     */
    public function testCastUsing()
    {
        $this->assertEquals(LinkCast::class, Link::castUsing([]));
    }
}
