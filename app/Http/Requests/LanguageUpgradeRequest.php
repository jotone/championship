<?php

namespace App\Http\Requests;

class LanguageUpgradeRequest extends DefaultFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'admin_lang' => ['nullable', 'string'],
            'main_lang'  => ['nullable', 'string'],
            'lang_list'  => ['nullable', 'array']
        ];
    }
}