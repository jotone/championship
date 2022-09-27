<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BasicApiController;
use App\Http\Requests\CompetitionGroupGameRequest;
use App\Models\{CompetitionGame, CompetitionTeam, Country};
use Carbon\Carbon;
use Illuminate\Http\{Request, Response};
use Illuminate\Support\Facades\Validator;

class CompetitionGroupGameController extends BasicApiController
{
    /**
     * Create competition game
     *
     * @param CompetitionGroupGameRequest $request
     * @return Response
     */
    public function store(CompetitionGroupGameRequest $request): Response
    {
        $args = $request->validated();
        $team = CompetitionTeam::where('group_id', $args['group_id'])->first();
        $args['entity'] = $team->entity;

        $game = CompetitionGame::create($args);

        return response($game, 201);
    }

    /**
     * Update competition game
     *
     * @param CompetitionGame $competition_group_game
     * @param Request $request
     * @return Response
     */
    public function update(CompetitionGame $competition_group_game, Request $request): Response
    {
        $args = $request->only(['host_team', 'guest_team', 'place', 'start_at']);

        $rules = [];
        foreach ($args as $key => $val) {
            switch ($key) {
                case 'host_team':
                    $table = Country::class == $competition_group_game->entity ? 'countries' : 'teams';
                    $rules[$key] = ['required', 'numeric', 'exists:' . $table . ',id'];
                    $competition_group_game->guest_team = $val;
                    break;
                case 'guest_team':
                    $table = Country::class == $competition_group_game->entity ? 'countries' : 'teams';
                    $rules[$key] = ['required', 'numeric', 'exists:' . $table . ',id'];
                    $competition_group_game->host_team = $val;
                    break;
                case 'place':
                    $rules[$key] = ['nullable', 'string'];
                    $competition_group_game->$key = $val;
                    break;
                case 'start_at':
                    $rules[$key] = ['nullable', 'string', 'date_format:d/M/Y H:i'];
                    $competition_group_game->$key = !empty($val)
                        ? Carbon::createFromFormat('d/M/Y H:i', $val)->subHours(3)
                        : null;
                    break;
            }
        }

        if (!empty($rules)) {
            $validator = Validator::make($args, $rules);

            if ($validator->fails()) {
                return response($validator->errors()->all(), 400);
            }

            $competition_group_game->save();
        }

        return response($competition_group_game);
    }

    /**
     * Remove competition game
     *
     * @param CompetitionGame $competition_group_game
     * @return Response
     */
    public function destroy(CompetitionGame $competition_group_game): Response
    {
        $competition_group_game->delete();

        return response([], 204);
    }
}