<?php

namespace App\Observers;

use App\Models\Post;

class PostObserver
{
    /**
     * Handle the Post "deleted" event.
     */
    public function deleted(Post $post): void
    {
        $post->comments()->forceDelete();
    }
}
