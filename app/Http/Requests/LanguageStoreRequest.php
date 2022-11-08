<?php

namespace App\Http\Requests;

use App\Traits\LanguageTrait;
use Illuminate\Validation\Rule;

class LanguageStoreRequest extends DefaultFormRequest
{
    use LanguageTrait;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'lang' => ['required', Rule::in($this->language_list)],
        ];
    }
}