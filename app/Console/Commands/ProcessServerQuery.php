<?php

namespace App\Console\Commands;

use App\Models\CompetitionGame;
use App\Models\CompetitionGroup;
use App\Models\ServerQueue;
use App\Models\UserForm;
use Illuminate\Console\Command;
use function Psy\debug;
use function Symfony\Component\String\s;

class ProcessServerQuery extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'server_queue:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $items = ServerQueue::where(['status' => 0])->take(50)->get();

        foreach ($items as $item) {
            // Get related user form
            $form = UserForm::with(['bets' => fn($q) => $q->with(['game', 'group'])])->firstWhere([
                'user_id'        => $item->user_id,
                'competition_id' => $item->competition_id
            ]);

            $user_points = 0;

            // Get user form bets
            foreach ($form->bets as $bet) {
                // Group games score
                if (!empty($bet->game_id) && $bet->game->accept) {
                    // Host team score
                    $host_score = [
                        'real' => $bet->game->score[$bet->game->host_team],
                        'user' => $bet->scores[$bet->game->host_team]
                    ];
                    // Guest team score
                    $guest_score = [
                        'real' => $bet->game->score[$bet->game->guest_team],
                        'user' => $bet->scores[$bet->game->guest_team]
                    ];
                    // If user guess Exact score
                    if ($host_score['real'] == $host_score['user'] && $guest_score['real'] == $guest_score['user']) {
                        $user_points += 3;
                    }

                    // If user guess winner
                    if (
                        ($host_score['real'] > $guest_score['real'] && $host_score['user'] > $guest_score['user'])
                        || ($host_score['real'] < $guest_score['real'] && $host_score['user'] < $guest_score['user'])
                        || ($host_score['real'] == $guest_score['real'] && $host_score['user'] == $guest_score['user'])
                    ) {
                        $user_points += 1;
                    }
                } else {
                    // Play-off games
                    $teams_in_group = [];
                    // Play off group games
                    $group_games = $bet->group->games()->where('accept', 1)->get();
                    // Get group teams
                    foreach ($group_games as $group) {
                        $teams_in_group[] = $group->host_team;
                        $teams_in_group[] = $group->guest_team;
                    }
                    $teams_in_group = array_values(array_unique($teams_in_group));
                    // Calculate match teams
                    $match_scores = count(array_intersect($bet->scores, $teams_in_group));
                    // Score multiplication value
                    $mult = $bet->group->games_number == 0 ? 0 : ($bet->group->games_number == 1 ? 2 : 1);
                    // Calculate user points
                    $user_points += $match_scores * $mult;
                    // Add bonus points
                    if ($bet->group->games_number == 8) {
                        // 1/8
                        switch ($match_scores) {
                            case 12:$user_points += 4;break;
                            case 13:$user_points += 6;break;
                            case 14:$user_points += 8;break;
                            case 15:$user_points += 9;break;
                            case 16:$user_points += 10;break;
                        }
                    } else if ($bet->group->games_number == 4) {
                        // 1/4
                        switch ($match_scores) {
                            case 6:$user_points += 4;break;
                            case 7:$user_points += 6;break;
                            case 8:$user_points += 8;break;
                        }
                    } else if ($bet->group->games_number == 2) {
                        // 1/2
                        switch ($match_scores) {
                            case 3:$user_points += 6;break;
                            case 4:$user_points += 8;break;
                        }
                    } else if ($bet->group->games_number <= 1) {
                        $group = CompetitionGroup::with('games')->firstWhere([
                            'competition_id' => $item->competition_id,
                            'games_number' => 1
                        ]);
                        if ($group->games->count()) {
                            if ($bet->group->games_number == 1) {
                                // Final
                                $user_points += count(array_intersect($bet->scores, array_keys($group->games[0]->score))) ? 8 : 0;
                            } else {
                                // Champion
                                $user_points += in_array($bet->scores[0], array_keys($group->games[0]->score)) ? 8 : 0;
                            }
                        }
                    }
                }
            }

            $form->update(['points' => $user_points]);
        }
        return 0;
    }
}