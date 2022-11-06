<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\BasicMainController;
use App\Models\{Competition, CompetitionGame, CustomPage, Settings};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class HomeController extends BasicMainController
{
    /**
     * Index page
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        // Get page slug
        $slug = str_replace('.index', '', $request->route()->getName());

        return $this->renderIndexPage('main.home.index', [
            // Get page data by slug
            'page_data' => CustomPage::firstWhere('slug', $slug)
        ]);
    }

    /**
     * Schedule page
     *
     * @return View
     */
    public function schedule(): View
    {
        return $this->renderIndexPage('main.home.schedule', [
            // Get schedule page data
            'page_data' => CustomPage::firstWhere('slug', 'schedule'),
        ]);
    }

    /**
     * Group results page
     *
     * @return View
     */
    public function groups(): View
    {
        return $this->renderIndexPage('main.home.groups', [
            // Get groups page data
            'page_data' => CustomPage::firstWhere('slug', 'schedule')
        ]);
    }

    /**
     * @return View
     */
    public function summary(): View
    {
        // Get session messages
        $messages = Session::has('messages') ? Session::get('messages') : [];
        Session::remove('messages');

        $competition = Competition::with([
            'userForms' => fn($q) => $q->with([
                'bets' => fn($bet_query) => $bet_query->with([
                    'group',
                    'game' => fn($game_query) => $game_query->where('accept', true)
                ])
            ])
        ])->firstWhere('slug', 'world-cup-2022');

        $group_data = [];

        foreach ($competition->userForms as $form) {
            foreach ($form->bets as $bet) {
                if (!isset($group_data[$bet->group_id])) {
                    $group_data[$bet->group_id] = [
                        'group_id' => md5($bet->group_id),
                        'name'     => $bet->group->name,
                        'position' => ($bet->group->stage + 1) * 100 + $bet->group->position
                    ];
                }

                if ($bet->group->stage > 0) {
                    $game = $bet->group->games()->where('accept', 1)->first();

                    if ($game) {
                        $game_teams = $game->entity::whereIn('id', $game->score ?? [])->get()
                            ->map(function ($model) {
                                $model->uuid = md5($model->id);
                                return $model;
                            })
                            ->pluck('ua', 'uuid')
                            ->toArray();
                        $match_scores = count(array_intersect($bet->scores, $game->score));
                        // Score multiplication value
                        $mult = $bet->group->games_number > 2 ? 1 : 2;
                        // Calculate user points
                        $points = $match_scores * $mult;
                        // Add bonus points
                        if ($bet->group->games_number == 8) {
                            // 1/8
                            switch ($match_scores) {
                                case 12:
                                    $points += 4;
                                    break;
                                case 13:
                                    $points += 6;
                                    break;
                                case 14:
                                    $points += 8;
                                    break;
                                case 15:
                                    $points += 9;
                                    break;
                                case 16:
                                    $points += 10;
                                    break;
                            }
                        } else if ($bet->group->games_number == 4) {
                            // 1/4
                            switch ($match_scores) {
                                case 6:
                                    $points += 4;
                                    break;
                                case 7:
                                    $points += 6;
                                    break;
                                case 8:
                                    $points += 8;
                                    break;
                            }
                        } else if ($bet->group->games_number == 2) {
                            // 1/2
                            switch ($match_scores) {
                                case 3:
                                    $points += 6;
                                    break;
                                case 4:
                                    $points += 8;
                                    break;
                            }
                        } else if ($bet->group->games_number == 1 && 4 == $match_scores) {
                            // Final
                            $points += 8;
                        } else if ($bet->group->games_number == 0 && $match_scores > 0) {
                            // Champion
                            $points += 6;
                        }

                        if (!isset($group_data[$game->group_id]['playOff'])) {
                            $group_data[$game->group_id]['playOff'] = [
                                'game_id' => md5($game->id),
                                'teams'   => $game_teams,
                                'users'   => []
                            ];
                        }

                        $group_data[$game->group_id]['playOff']['users'][] = [
                            'id'     => md5($form->user_id),
                            'name'   => $form->user->name,
                            'points' => $points,
                            'teams'  => $game->entity::whereIn('id', $bet->scores ?? [])->get()
                                ->map(function ($model) {
                                    $model->uuid = md5($model->id);
                                    return $model;
                                })
                                ->pluck('ua', 'uuid')
                                ->toArray()
                        ];
                    }
                } else {
                    $points = 0;
                    if ($bet->game) {
                        $real = $bet->game->score;
                        $user = $bet->scores;

                        // Calculate user points
                        if (
                            $real[$bet->game->host_team] == $user[$bet->game->host_team]
                            && $real[$bet->game->guest_team] == $user[$bet->game->guest_team]
                        ) {
                            // If user guess Exact score
                            $points = 3;
                        } else if (
                            // If user guess winner
                            (
                                $real[$bet->game->host_team] > $real[$bet->game->guest_team]
                                && $user[$bet->game->host_team] > $user[$bet->game->guest_team]
                            ) || (
                                $real[$bet->game->host_team] < $real[$bet->game->guest_team]
                                && $user[$bet->game->host_team] < $user[$bet->game->guest_team]
                            ) || (
                                $real[$bet->game->host_team] == $real[$bet->game->guest_team]
                                && $user[$bet->game->host_team] == $user[$bet->game->guest_team]
                            )
                        ) {
                            $points = 1;
                        }

                        $game_id = md5($bet->game->id);
                        if (!isset($group_data[$bet->game->group_id]['games'][$game_id])) {
                            $group_data[$bet->game->group_id]['games'][$game_id] = [
                                'host'  => [
                                    'id'   => md5($bet->game->host_team),
                                    'name' => $bet->game->hostTeam->ua
                                ],
                                'guest' => [
                                    'id'   => md5($bet->game->guest_team),
                                    'name' => $bet->game->guestTeam->ua
                                ],
                                'score' => $this->modifyScore($bet->game->score),
                                'users' => []
                            ];
                        }

                        $group_data[$bet->game->group_id]['games'][$game_id]['users'][] = [
                            'id'     => md5($form->user_id),
                            'name'   => $form->user->name,
                            'points' => $points,
                            'score'  => $this->modifyScore($bet->scores)
                        ];
                    }
                }
            }
        }

        return view('main.home.summary', [
            'groups'    => $group_data,
            'messages'  => $messages,
            // Get groups page data
            'page_data' => CustomPage::firstWhere('slug', 'summary'),
            // Settings
            'setup'     => $this->settingsData(),
        ]);
    }

    /**
     * Set id values to md5
     * @param array $score
     * @return array
     */
    protected function modifyScore(array $score): array
    {
        $result = [];
        foreach ($score as $id => $val) {
            $result[md5($id)] = $val;
        }
        return $result;
    }
}