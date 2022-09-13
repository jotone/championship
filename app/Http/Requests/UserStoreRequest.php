<?php

namespace App\Http\Requests;

use App\Models\Role;

class UserStoreRequest extends DefaultFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name'         => ['required', 'string'],
            'email'        => ['required', 'email', 'unique:users,email'],
            'img_url'      => ['nullable', 'file', 'mimes:jpeg,jpg,png'],
            'password'     => ['required', 'string', 'min:6'],
            'confirmation' => ['required', 'string', 'same:password'],
            'role_id'      => ['nullable', 'exists:roles,id']
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
            'role_id' => empty($this->request->get('role_id'))
                ? Role::where('slug', 'regular')->value('id')
                : $this->request->get('role_id')
        ]);
    }
}