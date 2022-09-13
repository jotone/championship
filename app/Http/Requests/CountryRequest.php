<?php

namespace App\Http\Requests;

class CountryRequest extends DefaultFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'code'    => ['required', 'string'],
            'en'      => ['required', 'string'],
            'ua'      => ['required', 'string'],
            'img_url' => ['nullable', 'file', 'mimes:svg']
        ];
    }
}
