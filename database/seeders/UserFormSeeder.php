<?php

namespace Database\Seeders;

use App\Models\{Competition, UserForm, UserFormBets};
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class UserFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($users)
    {
        $competition = Competition::with(['games', 'groups', 'teams'])->where('slug', 'world-cup-2022')->first();

        foreach ($users as $user) {
            $form = UserForm::create([
                'user_id'        => $user->id,
                'competition_id' => $competition->id
            ]);

            foreach ($competition->games as $game) {
                UserFormBets::create([
                    'user_form_id' => $form->id,
                    'group_id'     => $game->group_id,
                    'game_id'      => $game->id,
                    'scores'       => [
                        $game->host_team  => mt_rand(0, 4),
                        $game->guest_team => mt_rand(0, 4)
                    ]
                ]);
            }

            $team_ids = $competition->teams()->get()->pluck('entity_id')->toArray();

            foreach ($competition->groups()->where('stage', '>', 0)->get() as $group) {
                UserFormBets::create([
                    'user_form_id' => $form->id,
                    'group_id'     => $group->id,
                    'scores'       => Arr::random($team_ids, $group->games_number > 0 ? $group->games_number * 2 : 1)
                ]);
            }
        }
    }
}
