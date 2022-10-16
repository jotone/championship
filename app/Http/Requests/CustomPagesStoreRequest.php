<?php

namespace App\Http\Requests;

class CustomPagesStoreRequest extends DefaultFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name'             => ['required', 'string'],
            'url'              => ['required', 'string', 'unique:custom_pages,url'],
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
        $url = $this->request->get('url');
        $this->merge([
            'url'      => $url[0] !== '/' ? '/' . $url : $url,
            'editable' => checkboxResponseToBool($this->request->get('editable', false)),
            'enabled'  => checkboxResponseToBool($this->request->get('enabled', false))
        ]);
    }
}
