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
        $team = CompetitionTeam::where('group_id', $this->request->get('group_id'))->first();
        $table = !empty($team) && $team->entity == Country::class ? 'countries' : 'teams';

        return [
            'group_id'   => ['required', 'exists:competition_groups,id'],
            'host_team'  => ['required', 'exists:' . $table . ',id'],
            'guest_team' => ['required', 'exists:' . $table . ',id']
        ];
    }
}