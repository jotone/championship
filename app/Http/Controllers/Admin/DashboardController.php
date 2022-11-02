<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BasicAdminController;
use App\Models\{CompetitionGame, User, UserForm};
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends BasicAdminController
{
    public function index(Request $request): View
    {
        $next_games_date = CompetitionGame::select([
            'competition_group_games.id',
            'competition_group_games.group_id',
            'competition_group_games.start_at',
            'competition_groups.stage'
        ])
            ->leftJoin('competition_groups', 'competition_group_games.group_id', '=', 'competition_groups.id')
            ->where('competition_groups.stage', 0)
            ->where('competition_group_games.start_at', '>=', now())
            ->orderBy('competition_group_games.start_at')
            ->first();

        if ($next_games_date) {
            $date = $next_games_date->start_at->format('Y-m-d');
            $games = CompetitionGame::select([
                'competition_group_games.id',
                'competition_group_games.group_id',
                'competition_group_games.host_team',
                'competition_group_games.guest_team',
                'competition_group_games.entity',
                'competition_group_games.start_at',
                'competition_groups.competition_id',
                'competition_groups.stage',
            ])
                ->leftJoin('competition_groups', 'competition_group_games.group_id', '=', 'competition_groups.id')
                ->where('competition_groups.stage', 0)
                ->whereBetween('start_at', [$date . ' 00:00:00', $date . ' 23:59:59'])
                ->orderBy('start_at')
                ->get();
        } else {
            $games = [];
        }

        return $this->renderPage('admin.dashboard.index', $request, [
            'forms'       => UserForm::with('user')->orderBy('points', 'desc')->get(),
            'last_active' => User::orderBy('last_activity', 'desc')->get(),
            'next_games'  => $games,
            'title'       => 'Адмін панель',
        ]);
    }
}