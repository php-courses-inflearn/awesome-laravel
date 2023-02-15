<?php

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class Published extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(public readonly Post $post)
    {
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
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("[라라벨] '{$this->post->blog->display_name}' 에 새로운 글 '{$this->post->title}'")
            ->greeting('새로운 글이 작성되었습니다.')
            ->line(Str::substr($this->post->content, 0, 200))
            ->action('글 읽기', route('posts.show', $this->post));
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'post' => $this->post,
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
