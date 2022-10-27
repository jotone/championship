<?php

namespace App\Http\Requests;

class FormMessageUpdateRequest extends DefaultFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'topic'       => ['required', 'string'],
            'message'     => ['required', 'string'],
            'edit_reason' => ['nullable', 'string']
        ];
    }
}