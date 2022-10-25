<?php

namespace App\Http\Requests;

class ForumMessageStoreRequest extends DefaultFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'topic'   => ['required', 'string'],
            'parent'  => ['required', 'string'],
            'message' => ['required', 'string'],
        ];
    }
}