<?php

namespace Tests\Feature\Console;

use Illuminate\Console\Events\ScheduledTaskFinished;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Tests\TestCase;

class KernelTest extends TestCase
{
    public function testMailSendCommandSchedule(): void
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
                return Str::contains($event->task->command, 'mail:send --queue=emails');
            });
    }
}
