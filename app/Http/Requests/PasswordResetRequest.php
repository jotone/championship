<?php

namespace App\Http\Requests;

class PasswordResetRequest extends DefaultFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'password'     => ['required', 'string', 'min:6'],
            'confirmation' => ['required', 'string', 'same:password']
        ];
    }
}