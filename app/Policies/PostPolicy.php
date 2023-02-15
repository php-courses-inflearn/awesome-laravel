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
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user): bool
    {
        return $user->tokenCan('post:read');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Post $post): bool
    {
        return $user->tokenCan('post:read');
    }

    /**
     * Determine whether the user can create models.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, Blog $blog): bool
    {
        return $user->id === $blog->user_id &&
            $user->tokenCan('post:create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->blog->user_id &&
            $user->tokenCan('post:update');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Post $post): bool
    {
        return $user->id === $post->blog->user_id &&
            $user->tokenCan('post:delete');
    }
}
