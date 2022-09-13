<?php

namespace App\Http\Requests;

class TeamRequest extends DefaultFormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'en'         => ['required', 'string'],
            'ua'         => ['required', 'string'],
            'country_id' => ['required', 'exists:countries,id'],
            'img_url'    => ['nullable', 'file', 'mimes:jpeg,jpg,png,svg']
        ];
    }
}
