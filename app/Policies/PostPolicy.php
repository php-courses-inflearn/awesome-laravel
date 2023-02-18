<?php

namespace App\Policies;

use App\Models\Blog;
use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->tokenCan('post:read');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Post $post): bool
    {
        return $user->tokenCan('post:read');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Blog $blog): bool
    {
        return $user->id === $blog->user_id &&
            $user->tokenCan('post:create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->blog->user_id &&
            $user->tokenCan('post:update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Post $post): bool
    {
        return $user->id === $post->blog->user_id &&
            $user->tokenCan('post:delete');
    }
}
