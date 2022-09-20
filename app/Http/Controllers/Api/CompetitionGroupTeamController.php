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

        $team = $args['entity']::findOrFail($args['entity_id']);
        // Check the team already exists on the group
        if (CompetitionTeam::where($args)->count()) {
            return response([
                'errors' => [
                    'entity_id' => ['Team ' . $team->ua . ' already exists on the group "' . $group->name . '"']
                ]
            ], 400);
        }
        $args['position'] = CompetitionTeam::where('entity', $args['entity'])
            ->where('group_id', $args['group_id'])
            ->count();

        $competition_team = CompetitionTeam::create($args);

        foreach ($group->teams as $group_team) {
            if ($group_team->entity_id != $team->id) {
                CompetitionGame::create([
                    'group_id'   => $args['group_id'],
                    'host_team'  => $team->id,
                    'guest_team' => $group_team->entity_id,
                    'entity'     => $args['entity'],
                ]);
                CompetitionGame::create([
                    'group_id'   => $args['group_id'],
                    'host_team'  => $group_team->entity_id,
                    'guest_team' => $team->id,
                    'entity'     => $args['entity']
                ]);
            }
        }

        foreach ($group->games as $i => $game) {
            $host = $game->hostTeam;
            $guest = $game->guestTeam;
            $group->games[$i]['host_team'] = $host;
            $group->games[$i]['guest_team'] = $guest;
        }

        return response([
            'model' => $competition_team,
            'team'  => $team,
            'group' => $group
        ], 201);
    }
}