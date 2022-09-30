<?php

namespace App\Http\Requests;

class CompetitionGroupRequest extends DefaultFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name'           => ['nullable', 'string'],
            'competition_id' => ['required', 'exists:competitions,id'],
            'position'       => ['nullable', 'numeric'],
            'stage'          => ['nullable', 'numeric']
        ];
    }
}