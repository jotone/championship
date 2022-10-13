<?php

namespace App\Http\Requests;

use App\Rules\AlreadyExists;

class CompetitionUpdateRequest extends DefaultFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $id = is_numeric($this->route()->parameter('competition'))
            ? $this->route()->parameter('competition')
            : $this->route()->parameter('competition')->id;
        return [
            'name'          => ['required', 'string'],
            'slug'          => ['nullable', 'string', new AlreadyExists('competitions', $id)],
            'groups_number' => ['required', 'numeric', 'min:1', 'max:21'],
            'rounds'        => ['required', 'numeric', 'min:1', 'max:5'],
            'img_url'       => ['nullable', 'file', 'mimes:jpeg,jpg,png,svg'],
            'bg_color'      => ['nullable', 'string'],
            'text_color'    => ['nullable', 'string'],
            'start_at'      => ['required', 'string'],
            'finish_at'     => ['nullable', 'string'],
        ];
    }
}
