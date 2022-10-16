<?php

namespace App\Http\Requests;

use App\Rules\AlreadyExists;

class CustomPagesUpdateRequest extends CustomPagesStoreRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $id = is_numeric($this->route()->parameter('page'))
            ? $this->route()->parameter('page')
            : $this->route()->parameter('page')->id;
        return [
            'name'             => ['required', 'string'],
            'url'              => ['required', 'string', new AlreadyExists('custom_pages', $id)],
            'editable'         => ['required'],
            'enabled'          => ['required'],
            'meta_title'       => ['nullable', 'string'],
            'meta_description' => ['nullable', 'string'],
            'meta_keywords'    => ['nullable', 'string'],
            'content'          => ['nullable', 'string']
        ];
    }
}
