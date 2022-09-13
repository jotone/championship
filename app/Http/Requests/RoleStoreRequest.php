<?php

namespace App\Http\Requests;

use App\Rules\AlreadyExists;
use Illuminate\Support\Facades\Auth;

class RoleStoreRequest extends DefaultFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $min_level = Auth::user()->role->level;
        return [
            'name'        => ['required', 'string'],
            'slug'        => ['nullable', 'string', new AlreadyExists('roles')],
            'level'       => ['required', 'numeric', 'min:' . $min_level, 'max:255'],
            'permissions' => ['nullable', 'array']
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $slug = $this->request->get('slug', $this->request->get('name'));

        $this->merge([
            'slug' => generateUrl(!empty($slug) ? $slug : $this->request->get('name'))
        ]);
    }
}
