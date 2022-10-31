<?php

namespace App\Providers;

use App\Events\Published;
use App\Events\Subscribed;
use App\Listeners\SendPublishingNotification;
use App\Listeners\SendSubscriptionNotification;
use App\Models\Post;
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
            SendSubscriptionNotification::class,
        ],
        Published::class => [
            SendPublishingNotification::class,
        ],
    ];

    /**
     * The model observers to register.
     *
     * @var array
     */
    protected $observers = [
        Post::class => PostObserver::class,
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
