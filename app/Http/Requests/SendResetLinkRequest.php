<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendResetLinkRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:users|max:255',
        ];
    }
}
