<?php

namespace App\Services;

use App\Events\Published;
use App\Http\Controllers\AttachmentController;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Blog;
use App\Models\Post;
use Illuminate\Http\Request;

class PostService
{
    /**
     * 글쓰기
     *
     * @param  \App\Http\Requests\StorePostRequest  $request
     * @param  \App\Models\Blog  $blog
     * @return \App\Models\Post
     */
    public function store(StorePostRequest $request, Blog $blog)
    {
        $post = $blog->posts()->create(
            $request->only('title', 'content')
        );

        $this->attachments($request, $post);

        if ($blog->subscribers()->exists()) {
            event(new Published($blog->subscribers, $post));
        }

        return $post;
    }

    /**
     * 글 수정
     *
     * @param  \App\Http\Requests\UpdatePostRequest  $request
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $post->update(
            $request->only(['title', 'content'])
        );

        $this->attachments($request, $post);
    }

    /**
     * 글 삭제
     *
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function destroy(Post $post)
    {
        $post->delete();
    }

    /**
     * 파일 업로드
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return void
     */
    private function attachments(Request $request, Post $post)
    {
        if ($request->hasFile('attachments')) {
            app(AttachmentController::class)->store($request, $post);
        }
    }
}
