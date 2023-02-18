<?php

namespace App\Policies;

use App\Models\Attachment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttachmentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Post $post): bool
    {
        return $user->id === $post->blog->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Attachment $attachment): bool
    {
        return $user->id === $attachment->post->blog->user_id;
    }
}
