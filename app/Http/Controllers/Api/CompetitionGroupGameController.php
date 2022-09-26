<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BasicApiController;
use App\Models\CompetitionGame;
use Carbon\Carbon;
use Illuminate\Http\{Request, Response};
use Illuminate\Support\Facades\Validator;

class CompetitionGroupGameController extends BasicApiController
{
    /**
     * Update competition game
     *
     * @param CompetitionGame $competition_group_game
     * @param Request $request
     * @return Response
     */
    public function update(CompetitionGame $competition_group_game, Request $request): Response
    {
        $args = $request->only(['place', 'start_at']);

        $rules = [];
        foreach ($args as $key => $val) {
            switch ($key) {
                case 'place':
                    $rules[$key] = ['required', 'string'];
                    $competition_group_game->$key = $val;
                    break;
                case 'start_at':
                    $rules[$key] = ['required', 'string', 'date_format:d/M/Y H:i'];
                    $competition_group_game->$key = Carbon::createFromFormat('d/M/Y H:i', $val)->subHours(3);
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
}