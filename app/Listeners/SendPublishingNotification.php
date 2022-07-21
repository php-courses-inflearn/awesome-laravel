<?php

namespace App\Listeners;

use App\Events\Published;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Published as PublishedNotification;

class SendPublishingNotification implements ShouldQueue
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
    public function handle(Published $event)
    {
        Notification::send($event->subscribers, new PublishedNotification($event->post));
    }
}
