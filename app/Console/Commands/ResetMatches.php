<?php

namespace App\Console\Commands;

use App\Models\CompetitionGame;
use App\Models\CompetitionGroup;
use App\Models\CompetitionTeam;
use Illuminate\Console\Command;

class ResetMatches extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'matches:reset';

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
        $games = CompetitionGame::all();

        foreach ($games as $game) {
            $score = [];
            if ($game->host_team) {

                foreach ($game->score as $id => $val) {
                    $score[$id] = 0;
                }
                $game->score = $score;
            } else {
                $game->score = [];
            }
            $game->accept = 0;
            $game->save();
        }

        $teams = CompetitionTeam::all();

        foreach ($teams as $team) {
            $team->games = 0;
            $team->wins = 0;
            $team->draws = 0;
            $team->loses = 0;
            $team->balls = '0-0';
            $team->score = 0;
            $team->save();
        }
    }
}