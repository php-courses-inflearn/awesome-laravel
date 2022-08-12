<?php

namespace App\Mail;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Advertisement extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('(광고) 라라벨 커뮤니티의 최신글 살펴보기!')
            ->view('emails.advertisement', [
                'posts' => $this->posts()
            ]);
    }

    /**
     * 글
     *
     * @return mixed
     */
    private function posts()
    {
        return Post::latest()->limit(5)->get();
    }
}
