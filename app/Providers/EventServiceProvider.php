<?php

namespace App\Providers;

use App\Events\Published;
use App\Events\Subscribed;
use App\Listeners\SendPublishedNotification;
use App\Listeners\SendSubscribedNotification;
use App\Models\Attachment;
use App\Models\Post;
use App\Observers\AttachmentObserver;
use App\Observers\PostObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        Subscribed::class => [
            SendSubscribedNotification::class,
        ],
        Published::class => [
            SendPublishedNotification::class,
        ],
    ];

    /**
     * The model observers to register.
     *
     * @var array
     */
    protected $observers = [
        Post::class => PostObserver::class,
        Attachment::class => AttachmentObserver::class,
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
