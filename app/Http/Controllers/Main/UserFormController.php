<?php

namespace App\Http\Controllers\Main;

use App\Models\{Competition, CompetitionGame, UserForm, UserFormBets};
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Support\Facades\{Auth, DB, Session, Validator};
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
        // Get session messages
        $messages = Session::has('messages') ? Session::get('messages') : [];
        Session::remove('messages');
        // Competition model
        $competition = Competition::with(['groups', 'teams'])->where('slug', 'world-cup-2022')->first();

        $teamIDs = $competition->teams->pluck('entity_id')->toArray();

        $teams = !empty($teamIDs)
            ? $competition->teams[0]->entity::whereIn('id', $teamIDs)->orderBy('ua')->get()
            : [];

        $user_form = UserForm::with('bets')
            ->where('user_id', Auth::id())
            ->where('competition_id', $competition->id)
            ->first();

        $bets = [];

        foreach ($user_form->bets as $bet) {
            $data = (object) [
                'scores'  => $bet->scores,
                'points'  => $bet->points
            ];
            if (!empty($bet->game_id)) {
                $bets[$bet->group_id][$bet->game_id] = $data;
            } else {
                $bets[$bet->group_id] = $data;
            }
        }

        return view('main.user-form.index', [
            'competition' => $competition,
            'messages'    => $messages,
            'teams'       => $teams,
            'bets'        => $bets
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

        $validation = Validator::make($args, [
            'game'  => ['required', 'array'],
            'group' => ['required', 'array']
        ]);

        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation->messages());
        }

        // Start database transactions
        DB::beginTransaction();

        $user_form = UserForm::create([
            'user_id'        => $user->id,
            'competition_id' => $competition->id
        ]);

        // Save groups score
        foreach ($args['game'] as $game_id => $score) {
            // Convert group team scores to proper data ( convert values like, '00' => 0 or '020' => 20 )
            $score_data = [];
            foreach ($score as $team_id => $value) {
                $score_data[$team_id] = (int)$value;
            }
            // Game entity
            $game = CompetitionGame::findOrFail($game_id);
            // Create user form bets entity
            UserFormBets::create([
                'user_form_id' => $user_form->id,
                'group_id'     => $game->group_id,
                'game_id'      => $game_id,
                'scores'       => $score_data,
            ]);
        }

        // Save play-off winners
        foreach ($args['group'] as $group_id => $team_ids) {
            foreach ($team_ids as $team_id) {
                if ($team_id == '0') {
                    DB::rollBack();
                    return redirect()->back()->withErrors([
                        'error' => 'Невірно обрана команда'
                    ]);
                }
            }
            // Create user form bets entity
            UserFormBets::create([
                'user_form_id' => $user_form->id,
                'group_id'     => $group_id,
                'scores'       => $team_ids,
            ]);
        }
        DB::commit();

        return redirect()->back()->with([
            'messages' => [
                'success' => [
                    'Ваша анкету було збережено'
                ]
            ]
        ]);
    }
}