<?php

namespace App\Http\Requests;

class ForumTopicRequest extends DefaultFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name'        => ['required', 'string'],
            'url'         => ['required', 'string', 'unique:forum_topics,url'],
            'img_url'     => ['nullable', 'file', 'mimes:jpeg,jpg,png'],
            'description' => ['nullable', 'string'],
            'text'        => ['nullable', 'string'],
            'position'    => ['required', 'numeric', 'unique:forum_topics,position']
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'url' => generateUrl($this->request->get('name')),
        ]);
    }
}