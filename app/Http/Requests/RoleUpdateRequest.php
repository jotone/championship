<?php

namespace App\Http\Requests;

use App\Rules\AlreadyExists;
use Illuminate\Support\Facades\Auth;

class RoleUpdateRequest extends DefaultFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $id = is_numeric($this->route()->parameter('role'))
            ? $this->route()->parameter('role')
            : $this->route()->parameter('role')->id;
        $min_level = Auth::user()->role->level;
        return [
            'name'        => ['required', 'string'],
            'slug'        => ['required', 'string', new AlreadyExists('roles', $id)],
            'level'       => ['required', 'numeric', 'min:' . $min_level, 'max:255'],
            'permissions' => ['nullable', 'array']
        ];
    }
}
