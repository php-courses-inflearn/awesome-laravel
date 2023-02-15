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
     */
    public function store(array $data, Blog $blog): Post
    {
        /** @var \App\Models\Post $post */
        $post = $blog->posts()->create([
            'title' => $data['title'],
            'content' => $data['content'],
        ]);

        if (Arr::exists($data, 'attachments')) {
            $this->attachments($data['attachments'], $post);
        }

        if ($blog->subscribers()->exists()) {
            event(new Published($blog->subscribers, $post));
        }

        return $post;
    }

    /**
     * 글 수정
     */
    public function update(array $data, Post $post): void
    {
        $post->update([
            'title' => $data['title'],
            'content' => $data['content'],
        ]);

        if (Arr::exists($data, 'attachments')) {
            $this->attachments($data['attachments'], $post);
        }
    }

    /**
     * 글 삭제
     */
    public function destroy(Post $post): void
    {
        $post->delete();
    }

    /**
     * 파일 업로드
     *
     * @param  array<UploadedFile>  $attachments
     */
    private function attachments(array $attachments, Post $post): void
    {
        $data = [
            'attachments' => $attachments,
        ];

        $this->attachmentService->store($data, $post);
    }
}
