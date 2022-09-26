<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BasicApiController;
use App\Http\Requests\CompetitionGroupTeamRequest;
use App\Models\{CompetitionGame, CompetitionGroup, CompetitionTeam};
use Illuminate\Http\Response;

class CompetitionGroupTeamController extends BasicApiController
{
    /**
     * Fill competition group with teams and games
     *
     * @param CompetitionGroupTeamRequest $request
     * @return Response
     */
    public function store(CompetitionGroupTeamRequest $request): Response
    {
        $args = $request->validated();

        $group = CompetitionGroup::findOrFail($args['group_id']);

        // Search team
        $team = $args['entity']::find($args['entity_id']);
        if (!$team) {
            $team = $args['entity']::where('en', 'like', '%' . $args['searchSelect'] . '%')
                ->orWhere('ua', 'like', '%' . $args['searchSelect'] . '%')
                ->first();
        }

        abort_if(!$team, 404);

        unset($args['searchSelect']);
        // Check the team already exists on the group
        if (CompetitionTeam::where($args)->count()) {
            return response([
                'errors' => [
                    'entity_id' => ['Team ' . $team->ua . ' already exists on the group "' . $group->name . '"']
                ]
            ], 400);
        }

        CompetitionTeam::create($args);

        $group->fresh();

        foreach ($group->teams as $group_team) {
            for ($i = 0; $i < $group->competition->rounds; $i++) {
                if ($group_team->entity_id != $team->id) {
                    CompetitionGame::create([
                        'group_id'   => $args['group_id'],
                        'host_team'  => $team->id,
                        'guest_team' => $group_team->entity_id,
                        'entity'     => $args['entity'],
                    ]);
                }
            }
        }

        return response([
            'team'  => $team,
            'group' => CompetitionGroup::with(['teams', 'games'])->find($args['group_id'])
        ], 201);
    }
}