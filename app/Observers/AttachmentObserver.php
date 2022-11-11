<?php

namespace App\Observers;

use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;

class AttachmentObserver
{
    /**
     * Handle the Post "deleting" event.
     *
     * @param  \App\Models\Attachment  $attachment
     * @return void
     */
    public function deleting(Attachment $attachment)
    {
        Storage::disk('public')->delete($attachment->name);
    }
}
