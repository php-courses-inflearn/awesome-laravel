<?php

namespace App\Notifications;

use App\Mail\Subscribed as SubscribedMailable;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class Subscribed extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(
        public readonly User $user,
        public readonly Blog $blog
    ) {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     */
    public function via($notifiable): array
    {
        return ['mail', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toMail($notifiable): SubscribedMailable
    {
        $address = $notifiable instanceof AnonymousNotifiable
            ? $notifiable->routeNotificationFor('mail')
            : $notifiable->email;

        return (new SubscribedMailable($this->user, $this->blog))
            ->to($address);
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'user' => $this->user,
        ]);
    }

    /**
     * Determine which queues should be used for each notification channel.
     *
     * @codeCoverageIgnore
     */
    public function viaQueues(): array
    {
        return [
            'mail' => 'emails',
            'broadcast' => 'broadcasts',
        ];
    }
}
