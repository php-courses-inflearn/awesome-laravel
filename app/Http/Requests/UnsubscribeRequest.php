<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UnsubscribeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'blog_id' => 'required|exists:blogs,id',
        ];
    }
}
