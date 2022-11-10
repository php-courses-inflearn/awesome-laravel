<?php

namespace App\Policies;

use App\Models\Attachment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Http\Request;

class AttachmentPolicy
{
    use HandlesAuthorization;

    /**
     * @var Post|null
     */
    private readonly ?Post $post;

    /**
     * @param  \Illuminate\Http\Request  $request
     */
    public function __construct(Request $request)
    {
        $this->post = $request->post;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->id === $this->post->blog->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Attachment  $attachment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Attachment $attachment)
    {
        return $user->id === $attachment->post->blog->user_id;
    }
}
