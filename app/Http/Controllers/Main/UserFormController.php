<?php

namespace App\Http\Controllers\Main;

use App\Models\{Competition, CompetitionGame, UserForm};
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Support\Facades\{Auth, DB};
use Illuminate\View\View;

class UserFormController
{
    /**
     * View User Form page
     *
     * @return View
     */
    public function index(): View
    {
        $competition = Competition::with(['groups', 'teams'])->where('slug', 'world-cup-2022')->first();

        $teamIDs = $competition->teams->pluck('entity_id')->toArray();

        $teams = !empty($teamIDs)
            ? $competition->teams[0]->entity::whereIn('id', $teamIDs)->orderBy('ua')->get()
            : [];

        return view('main.user-form.index', [
            'competition' => $competition,
            'teams'       => $teams
        ]);
    }

    /**
     * Save User Form
     *
     * @param Competition $competition
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Competition $competition, Request $request): RedirectResponse
    {
        // Current User model
        $user = Auth::user();
        // Request data
        $args = $request->only(['game', 'group']);
        // Start database transactions
        DB::beginTransaction();

        // Save groups score
        foreach ($args['game'] as $game_id => $score) {
            // Convert group team scores to proper data ( convert values like, '00' => 0 or '020' => 20 )
            $score_data = [];
            foreach ($score as $team_id => $value) {
                $score_data[$team_id] = (int)$value;
            }
            // Game entity
            $game = CompetitionGame::findOrFail($game_id);
            if (empty($game)) dd($game, $game_id, $args);
            // Create user form entity
            UserForm::create([
                'user_id'        => $user->id,
                'competition_id' => $competition->id,
                'group_id'       => $game->group_id,
                'game_id'        => $game_id,
                'scores'         => $score_data,
            ]);
        }

        // Save play-off winners
        foreach ($args['group'] as $group_id => $team_ids) {
            foreach ($team_ids as $team_id) {
                if ($team_id == '0') {
                    DB::rollBack();
                    return redirect()->back()->withErrors([
                        'error' => 'Невірно вказана команда'
                    ]);
                }
            }
            // Create user form entity
            UserForm::create([
                'user_id'        => $user->id,
                'competition_id' => $competition->id,
                'group_id'       => $group_id,
                'scores'         => $team_ids,
            ]);
        }
        DB::commit();

        return redirect()->back()->with([
            'message' => 'Ваша анкету було збережено'
        ]);
    }
}