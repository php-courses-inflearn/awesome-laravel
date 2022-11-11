<?php

namespace Tests\Feature\Mail;

use App\Mail\Advertisement;
use Tests\TestCase;

class AdvertisementTest extends TestCase
{
    /**
     * 광고 이메일 테스트
     *
     * @return void
     */
    public function testAdvertisement()
    {
        $mailable = new Advertisement();

        $mailable->assertSeeInOrderInHtml(
            $mailable->posts()->map->title->all()
        );
    }
}
