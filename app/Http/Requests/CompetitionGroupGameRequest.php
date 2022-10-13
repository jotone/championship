<?php

namespace App\Http\Requests;

use App\Models\{CompetitionTeam, Country};

class CompetitionGroupGameRequest extends DefaultFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        if (empty($this->request->get('entity'))) {
            $team = CompetitionTeam::firstWhere(['group_id' => $this->request->get('group_id')]);
            $entity = $team->entity;
        } else {
            $entity = $this->request->get('entity');
        }
        $table = $entity == Country::class ? 'countries' : 'teams';

        return [
            'group_id'   => ['required', 'exists:competition_groups,id'],
            'entity'     => ['nullable', 'string'],
            'host_team'  => ['required', 'exists:' . $table . ',id'],
            'guest_team' => ['required', 'exists:' . $table . ',id']
        ];
    }
}