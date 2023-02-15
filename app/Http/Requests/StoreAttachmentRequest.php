<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttachmentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'attachments.*' => 'nullable|image',
        ];
    }
}
