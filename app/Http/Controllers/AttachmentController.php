<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Post;
use App\Services\AttachmentService;
use Illuminate\Http\Request;

class AttachmentController extends Controller
{
    /**
     * AttachmentController
     */
    public function __construct(
        private readonly AttachmentService $attachmentService
    ) {
        $this->authorizeResource(Attachment::class, 'attachment');
    }

    /**
     * 파일첨부
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function store(Request $request, Post $post)
    {
        $data = [
            'attachments' => $request->file('attachments'),
        ];

        $this->attachmentService->store($data, $post);
    }

    /**
     * 첨부파일 삭제
     *
     * @param  \App\Models\Attachment  $attachment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Attachment $attachment)
    {
        $this->attachmentService->destroy($attachment);

        return back();
    }
}
