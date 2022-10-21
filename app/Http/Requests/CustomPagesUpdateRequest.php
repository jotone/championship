<?php

namespace App\Http\Requests;

use App\Rules\AlreadyExists;

class CustomPagesUpdateRequest extends DefaultFormRequest
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
            'url'              => ['nullable', 'string', new AlreadyExists('custom_pages', $id)],
            'slug'             => ['nullable', 'string', new AlreadyExists('custom_pages', $id)],
            'editable'         => ['required'],
            'enabled'          => ['required'],
            'meta_title'       => ['nullable', 'string'],
            'meta_description' => ['nullable', 'string'],
            'meta_keywords'    => ['nullable', 'string'],
            'content'          => ['nullable', 'string']
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $url = $this->request->get('url', '');
        $this->merge([
            'url'      => !empty($url) ? ($url[0] !== '/' ? '/' . $url : $url) : null,
            'editable' => checkboxResponseToBool($this->request->get('editable', false)),
            'enabled'  => checkboxResponseToBool($this->request->get('enabled', false))
        ]);
    }
}
