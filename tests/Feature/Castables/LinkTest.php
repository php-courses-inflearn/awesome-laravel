<?php

namespace Tests\Feature\Castables;

use App\Castables\Link;
use App\Casts\Link as LinkCast;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LinkTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCastUsing()
    {
        $this->assertEquals(LinkCast::class, Link::castUsing([]));
    }
}
