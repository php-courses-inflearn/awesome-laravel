<?php

namespace Tests\Feature\Console;

use App\Mail\Advertisement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SendEmailsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * mail:send 명령어 테스트
     *
     * @return void
     */
    public function testSendEmails()
    {
        Mail::fake();

        $this->artisan('mail:send --queue=emails')
            ->assertSuccessful();

        Mail::assertQueued(Advertisement::class);
    }
}
