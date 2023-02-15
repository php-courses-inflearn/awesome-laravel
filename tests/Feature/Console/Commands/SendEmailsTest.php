<?php

namespace Tests\Feature\Console\Commands;

use App\Mail\Advertisement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SendEmailsTest extends TestCase
{
    use RefreshDatabase;

    public function testMailSendCommandQueuesAdvertisementMailable(): void
    {
        Mail::fake();

        User::factory(10)->create();

        $this->artisan('mail:send --queue=emails')
            ->assertSuccessful();

        Mail::assertQueued(Advertisement::class);
    }
}
