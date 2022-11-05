<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Post;
use Illuminate\Http\Request;

class AttachmentController extends Controller
{
    /**
     * AttachmentController
     */
    public function __construct()
    {
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
        foreach ($request->file('attachments') as $attachment) {
            $attachment->storePublicly('attachments', 'public');

            $post->attachments()->create([
                'original_name' => $attachment->getClientOriginalName(),
                'name' => $attachment->hashName('attachments'),
            ]);
        }
    }

    /**
     * 첨부파일 삭제
     *
     * @param  \App\Models\Attachment  $attachment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Attachment $attachment)
    {
        $attachment->delete();

        return back();
    }
}
