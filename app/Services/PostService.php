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
     * @param StorePostRequest $request
     * @param Blog $blog
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(StorePostRequest $request, Blog $blog)
    {
        $post = $blog->posts()->create(
            $request->only('title', 'content')
        );

        $this->attachments($request, $post);

        event(new Published($blog->subscribers, $post));

        return $post;
    }

    /**
     * 글 수정
     *
     * @param UpdatePostRequest $request
     * @param Post $post
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
     * @param Post $post
     * @return void
     */
    public function destroy(Post $post)
    {
        $post->delete();
    }

    /**
     * 파일 업로드
     *
     * @param Request $request
     * @param $post
     * @return void
     */
    private function attachments(Request $request, $post)
    {
        if ($request->hasFile('attachments')) {
            app(AttachmentController::class)->store($request, $post);
        }
    }
}
