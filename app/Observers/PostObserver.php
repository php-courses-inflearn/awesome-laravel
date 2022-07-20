<?php

namespace App\Observers;

use App\Models\Post;

class PostObserver
{
    /**
     * Handle the Post "deleting" event.
     *
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function deleting(Post $post)
    {
        $post->comments->each->forceDelete();
    }
}
