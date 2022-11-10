<?php

namespace Tests\Feature\Console;

use Illuminate\Console\Events\ScheduledTaskFinished;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class KernelTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testMailSendCommandSchedule()
    {
        Event::fake();

        $date = now()
            ->startOfWeek()
            ->weekday(Schedule::MONDAY)
            ->hour(8);

        $this->travelTo($date);
        $this->artisan('schedule:run');

        Event::assertDispatched(
            ScheduledTaskFinished::class,
            function (ScheduledTaskFinished $event) {
                $this->assertStringContainsString('mail:send --queue=emails', $event->task->command);

                return true;
            });
    }
}
