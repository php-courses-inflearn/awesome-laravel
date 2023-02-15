<?php

namespace App\Listeners;

use App\Events\Published;
use App\Notifications\Published as PublishedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SendPublishedNotification implements ShouldQueue
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
    public function handle(Published $event): void
    {
        Notification::send($event->subscribers, new PublishedNotification($event->post));
    }
}
