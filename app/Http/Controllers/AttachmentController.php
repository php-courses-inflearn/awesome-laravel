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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Attachment  $attachment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attachment $attachment)
    {
        $attachment->delete();

        return back();
    }
}
