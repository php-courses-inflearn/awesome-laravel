<?php

namespace App\Listeners;

use App\Events\Subscribed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\Subscribed as SubscribedNotification;

class SendSubscriptionNotification implements ShouldQueue
{
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
     *
     * @param  object  $event
     * @return void
     */
    public function handle(Subscribed $event)
    {
        $event->blog->user->notify(new SubscribedNotification($event->user, $event->blog));
    }
}
