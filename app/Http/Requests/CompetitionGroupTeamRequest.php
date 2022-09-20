<?php

namespace App\Http\Requests;

use App\Models\Country;

class CompetitionGroupTeamRequest extends DefaultFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $table = $this->request->get('entity') == Country::class ? 'countries' : 'teams';

        return [
            'group_id'  => ['required', 'exists:competition_groups,id'],
            'entity'    => ['required', 'string'],
            'entity_id' => ['required', 'exists:' . $table . ',id'],
        ];
    }
}