<?php

namespace App\Services;

use App\Events\Published;
use App\Models\Blog;
use App\Models\Post;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;

class PostService
{
    public function __construct(
       private readonly AttachmentService $attachmentService
    ) {
    }

    /**
     * 글쓰기
     *
     * @param  array  $data
     * @param  \App\Models\Blog  $blog
     * @return \App\Models\Post
     */
    public function store(array $data, Blog $blog)
    {
        /** @var \App\Models\Post $post */
        $post = $blog->posts()->create([
            'title' => $data['title'],
            'content' => $data['content'],
        ]);

        if (Arr::exists($data,'attachments')) {
            $this->attachments($data['attachments'], $post);
        }

        if ($blog->subscribers()->exists()) {
            event(new Published($blog->subscribers, $post));
        }

        return $post;
    }

    /**
     * 글 수정
     *
     * @param  array  $data
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function update(array $data, Post $post)
    {
        $post->update([
            'title' => $data['title'],
            'content' => $data['content'],
        ]);

        if (Arr::exists($data,'attachments')) {
            $this->attachments($data['attachments'], $post);
        }
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
     * @param  array<UploadedFile>  $attachments
     * @param  \App\Models\Post  $post
     * @return void
     */
    private function attachments(array $attachments, Post $post)
    {
        $data = [
            'attachments' => $attachments,
        ];

        $this->attachmentService->store($data, $post);
    }
}
