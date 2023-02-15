<?php

namespace App\Listeners;

use App\Events\Subscribed;
use App\Notifications\Subscribed as SubscribedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendSubscribedNotification implements ShouldQueue
{
    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue = 'listeners';

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Subscribed $event): void
    {
        $event->blog->user->notify(new SubscribedNotification($event->user, $event->blog));
    }
}
