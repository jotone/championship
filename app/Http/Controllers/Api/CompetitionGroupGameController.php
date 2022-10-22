<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BasicApiController;
use App\Http\Requests\CompetitionGroupGameRequest;
use App\Models\{CompetitionGame, CompetitionTeam, Country, ServerQueue, UserForm};
use Carbon\Carbon;
use Illuminate\Http\{Request, Response};
use Illuminate\Support\Facades\{DB, Validator};

class CompetitionGroupGameController extends BasicApiController
{
    /**
     * Get competition game list
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        return $this->renderIndexPage($request, CompetitionGame::class);
    }

    /**
     * View competition game
     *
     * @param CompetitionGame $competition_group_game
     * @return Response
     */
    public function show(CompetitionGame $competition_group_game): Response
    {
        return response($competition_group_game);
    }

    /**
     * Create competition game
     *
     * @param CompetitionGroupGameRequest $request
     * @return Response
     */
    public function store(CompetitionGroupGameRequest $request): Response
    {
        $args = $request->validated();

        if (empty($args['entity'])) {
            $team = CompetitionTeam::firstWhere('group_id', $args['group_id']);
            $args['entity'] = $team->entity;
        }

        $args['score'] = [
            $args['host_team']  => 0,
            $args['guest_team'] => 0
        ];

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
        $args = $request->only(['accept', 'host_team', 'guest_team', 'place', 'start_at', 'score']);

        $rules = [];

        DB::beginTransaction();
        foreach ($args as $key => $val) {
            switch ($key) {
                case 'accept':
                    $rules[$key] = ['required', 'numeric', 'min:0', 'max:1'];
                    $competition_group_game->$key = $val;

                    $forms = UserForm::where('competition_id', $competition_group_game->group->competition_id)->get();
                    foreach ($forms as $form) {
                        if (!ServerQueue::where([
                            'competition_id' => $competition_group_game->group->competition_id,
                            'user_id'        => $form->user_id,
                            'status'         => 0
                        ])->count()) {
                            ServerQueue::create([
                                'competition_id' => $competition_group_game->group->competition_id,
                                'user_id'        => $form->user_id
                            ]);
                        }
                    }
                    break;
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
                case 'score':
                    $rules[$key] = ['required', 'array'];
                    $competition_group_game->$key = $val;
                    break;
                case 'start_at':
                    $rules[$key] = ['nullable', 'string', 'date_format:j/M/Y H:i'];
                    $competition_group_game->$key = !empty($val)
                        ? Carbon::createFromFormat('j/M/Y H:i', $val)
                        : null;
                    break;
            }
        }

        if (!empty($rules)) {
            $validator = Validator::make($args, $rules);

            if ($validator->fails()) {
                DB::rollBack();
                return response($validator->errors()->all(), 400);
            }

            $competition_group_game->save();
            DB::commit();
            if ($competition_group_game->group->stage == 0) {
                $this->updateGroupsScore($competition_group_game->group);
            }
        }

        return response($competition_group_game->group()->with(['games', 'teams'])->first());
    }

    /**
     * Remove competition game
     *
     * @param CompetitionGame $competition_group_game
     * @return Response
     * @throws \Exception
     */
    public function destroy(CompetitionGame $competition_group_game): Response
    {
        $competition_group_game->delete();

        return response([], 204);
    }

    /**
     * Update scores for group
     * @param $group
     * @return void
     */
    protected function updateGroupsScore($group): void
    {
        $result = [];
        $entity = null;
        foreach ($group->games()->where('accept', 1)->get() as $game) {
            $host_team = $game->hostTeam;
            $guest_team = $game->guestTeam;

            if (!isset($result[$host_team->id])) {
                $result[$host_team->id] = $this->createTeamArray();
            }
            if (!isset($result[$guest_team->id])) {
                $result[$guest_team->id] = $this->createTeamArray();
            }
            if ($game->score[$host_team->id] == $game->score[$guest_team->id]) {
                $score = array_values($game->score)[0] ?? 0;
                $result[$host_team->id] = $this->updateDraw($result[$host_team->id], $score);
                $result[$guest_team->id] = $this->updateDraw($result[$guest_team->id], $score);
            } else {
                $result = $game->score[$host_team->id] > $game->score[$guest_team->id]
                    ? $this->updateWinner($result, $host_team->id, $guest_team->id, $game->score)
                    : $this->updateWinner($result, $guest_team->id, $host_team->id, $game->score);
            }
            if (is_null($entity)) {
                $entity = $game->entity;
            }
        }
        if (!is_null($entity)) {
            foreach ($result as $id => $group_team_data) {
                $group_team = CompetitionTeam::where('group_id', $group->id)
                    ->where('entity', $entity)
                    ->where('entity_id', $id)
                    ->firstOrFail();

                $item = $result[$group_team->entity_id];

                $group_team->games = $item->games;
                $group_team->wins = $item->wins;
                $group_team->draws = $item->draws;
                $group_team->loses = $item->loses;
                $group_team->score = $item->score;
                $group_team->balls = $item->goals . '-' . $item->misses;
                $group_team->save();
            }
        }
    }

    /**
     * Update team values for draw
     * @param $team
     * @param $score
     * @return object
     */
    protected function updateDraw($team, $score): object
    {
        $team->games++;
        $team->draws++;
        $team->score++;
        $team->goals += $score;
        $team->misses += $score;

        return $team;
    }

    /**
     * Update team values for win and lose
     * @param $result
     * @param $winner
     * @param $loser
     * @param $score
     * @return mixed
     */
    protected function updateWinner($result, $winner, $loser, $score): mixed
    {
        $result[$winner]->games++;
        $result[$winner]->wins++;
        $result[$winner]->score += 3;
        $result[$winner]->goals += $score[$winner];
        $result[$winner]->misses += $score[$loser];

        $result[$loser]->games++;
        $result[$loser]->loses++;
        $result[$loser]->goals += $score[$loser];
        $result[$loser]->misses += $score[$winner];

        return $result;
    }

    /**
     * Default team results array
     *
     * @return object
     */
    protected function createTeamArray(): object
    {
        return (object)[
            'games'  => 0,
            'wins'   => 0,
            'draws'  => 0,
            'loses'  => 0,
            'goals'  => 0,
            'misses' => 0,
            'score'  => 0
        ];
    }
}