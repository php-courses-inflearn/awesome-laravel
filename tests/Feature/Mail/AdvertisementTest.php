<?php

namespace Tests\Feature\Mail;

use App\Mail\Advertisement;
use Tests\TestCase;

class AdvertisementTest extends TestCase
{
    /**
     * Advertisement 이메일 테스트
     *
     * @return void
     */
    public function testAdvertisement()
    {
        $mailable = new Advertisement();

        $mailable->assertHasSubject(
            '(광고) 라라벨 커뮤니티의 최신글 살펴보기!'
        );

        $mailable->assertSeeInOrderInHtml(
            $mailable->posts()->pluck('title')->toArray()
        );
    }
}
