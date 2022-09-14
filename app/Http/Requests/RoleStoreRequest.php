<?php

namespace App\Http\Requests;

use App\Rules\AlreadyExists;
use App\Traits\RequestSlugTrait;
use Illuminate\Support\Facades\Auth;

class RoleStoreRequest extends DefaultFormRequest
{
    use RequestSlugTrait;

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
}
