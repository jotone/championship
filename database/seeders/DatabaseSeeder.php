<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\{Competition, CompetitionGame, CompetitionGroup, CompetitionTeam, Country, Team, User};
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(2)->create();

        Team::factory(32)->create();

        $competition = Competition::factory()->create([
            'name'          => 'Чемпіонат світу 2022',
            'slug'          => 'world-cup-2022',
            'groups_number' => 8,
            'rounds'        => 1,
            'start_at'      => '2022-11-20 00:00:00',
            'finish_at'     => '2022-12-18 23:59:59'
        ]);

        $team_arr = [
            'entity' => Country::class,
            'games'  => 0,
            'wins'   => 0,
            'draws'  => 0,
            'loses'  => 0,
            'balls'  => '0-0',
            'score'  => 0,
        ];
        $game_arr = [
            'entity' => Country::class,
            'place'  => null,
        ];

        for ($i = 0; $i < 8; $i++) {
            $group = CompetitionGroup::create([
                'name'           => 'Group ' . chr($i + 65),
                'competition_id' => $competition->id,
                'position'       => $i
            ]);

            $game_arr['group_id'] = $group->id;
            switch ($i) {
                case 0:
                    foreach ([67, 172, 193, 211] as $country_id) {
                        CompetitionTeam::create(array_merge([
                            'group_id'  => $group->id,
                            'entity_id' => $country_id
                        ], $team_arr));
                    }

                    // Ecuador vs Senegal
                    CompetitionGame::create(array_merge([
                        'host_team'  => 67,
                        'guest_team' => 211,
                        'start_at'   => '2022-11-29 17:00:00',
                        'score'      => [67 => 0, 211 => 0]
                    ], $game_arr));
                    // Ecuador vs Netherlands
                    CompetitionGame::create(array_merge([
                        'host_team'  => 67,
                        'guest_team' => 172,
                        'start_at'   => '2022-11-25 18:00:00',
                        'score'      => [67 => 0, 172 => 0]
                    ], $game_arr));
                    // Ecuador vs Qatar
                    CompetitionGame::create(array_merge([
                        'host_team'  => 67,
                        'guest_team' => 193,
                        'start_at'   => '2022-11-20 18:00:00',
                        'score'      => [67 => 0, 193 => 0]
                    ], $game_arr));
                    // Senegal vs Netherlands
                    CompetitionGame::create(array_merge([
                        'host_team'  => 211,
                        'guest_team' => 172,
                        'start_at'   => '2022-11-21 18:00:00',
                        'score'      => [211 => 0, 172 => 0]
                    ], $game_arr));
                    // Senegal vs Qatar
                    CompetitionGame::create(array_merge([
                        'host_team'  => 211,
                        'guest_team' => 193,
                        'start_at'   => '2022-11-25 15:00:00',
                        'score'      => [211 => 0, 193 => 0]
                    ], $game_arr));
                    // Netherlands vs Qatar
                    CompetitionGame::create(array_merge([
                        'host_team'  => 172,
                        'guest_team' => 193,
                        'start_at'   => '2022-11-29 17:00:00',
                        'score'      => [172 => 0, 193 => 0]
                    ], $game_arr));
                    break;
                case 1:
                    foreach ([240, 258, 114, 260] as $country_id) {
                        CompetitionTeam::create(array_merge([
                            'group_id'  => $group->id,
                            'entity_id' => $country_id
                        ], $team_arr));
                    }

                    // USA vs England
                    CompetitionGame::create(array_merge([
                        'host_team'  => 240,
                        'guest_team' => 258,
                        'start_at'   => '2022-11-25 21:00:00',
                        'score'      => [240 => 0, 258 => 0]
                    ], $game_arr));
                    // USA vs Iran
                    CompetitionGame::create(array_merge([
                        'host_team'  => 240,
                        'guest_team' => 114,
                        'start_at'   => '2022-11-29 21:00:00',
                        'score'      => [240 => 0, 114 => 0]
                    ], $game_arr));
                    // USA vs Wales
                    CompetitionGame::create(array_merge([
                        'host_team'  => 240,
                        'guest_team' => 260,
                        'start_at'   => '2022-11-21 21:00:00',
                        'score'      => [240 => 0, 260 => 0]
                    ], $game_arr));
                    // England vs Iran
                    CompetitionGame::create(array_merge([
                        'host_team'  => 258,
                        'guest_team' => 114,
                        'start_at'   => '2022-11-21 15:00:00',
                        'score'      => [258 => 0, 114 => 0]
                    ], $game_arr));
                    // England vs Wales
                    CompetitionGame::create(array_merge([
                        'host_team'  => 258,
                        'guest_team' => 260,
                        'start_at'   => '2022-11-29 21:00:00',
                        'score'      => [258 => 0, 260 => 0]
                    ], $game_arr));
                    // Iran vs Wales
                    CompetitionGame::create(array_merge([
                        'host_team'  => 114,
                        'guest_team' => 260,
                        'start_at'   => '2022-11-25 12:00:00',
                        'score'      => [114 => 0, 260 => 0]
                    ], $game_arr));
                    break;
                case 2:
                    foreach ([163, 199, 11, 185] as $country_id) {
                        CompetitionTeam::create(array_merge([
                            'group_id'  => $group->id,
                            'entity_id' => $country_id
                        ], $team_arr));
                    }

                    // Mexico vs Saudi Arabia
                    CompetitionGame::create(array_merge([
                        'host_team'  => 163,
                        'guest_team' => 199,
                        'start_at'   => '2022-11-30 21:00:00',
                        'score'      => [163 => 0, 199 => 0]
                    ], $game_arr));
                    // Mexico vs Argentina
                    CompetitionGame::create(array_merge([
                        'host_team'  => 163,
                        'guest_team' => 11,
                        'start_at'   => '2022-11-26 21:00:00',
                        'score'      => [163 => 0, 11 => 0]
                    ], $game_arr));
                    // Mexico vs Poland
                    CompetitionGame::create(array_merge([
                        'host_team'  => 163,
                        'guest_team' => 185,
                        'start_at'   => '2022-11-22 18:00:00',
                        'score'      => [163 => 0, 185 => 0]
                    ], $game_arr));
                    // Saudi Arabia vs Argentina
                    CompetitionGame::create(array_merge([
                        'host_team'  => 199,
                        'guest_team' => 11,
                        'start_at'   => '2022-11-22 12:00:00',
                        'score'      => [199 => 0, 11 => 0]
                    ], $game_arr));
                    // Saudi Arabia vs Poland
                    CompetitionGame::create(array_merge([
                        'host_team'  => 199,
                        'guest_team' => 185,
                        'start_at'   => '2022-11-26 16:00:00',
                        'score'      => [199 => 0, 185 => 0]
                    ], $game_arr));
                    // Argentina vs Poland
                    CompetitionGame::create(array_merge([
                        'host_team'  => 11,
                        'guest_team' => 185,
                        'start_at'   => '2022-11-30 21:00:00',
                        'score'      => [11 => 0, 185 => 0]
                    ], $game_arr));
                    break;
                case 3:
                    foreach ([80, 62, 230, 14] as $country_id) {
                        CompetitionTeam::create(array_merge([
                            'group_id'  => $group->id,
                            'entity_id' => $country_id
                        ], $team_arr));
                    }

                    // France vs Denmark
                    CompetitionGame::create(array_merge([
                        'host_team'  => 80,
                        'guest_team' => 62,
                        'start_at'   => '2022-11-26 18:00:00',
                        'score'      => [80 => 0, 62 => 0]
                    ], $game_arr));
                    // France vs Tunisia
                    CompetitionGame::create(array_merge([
                        'host_team'  => 80,
                        'guest_team' => 230,
                        'start_at'   => '2022-11-30 17:00:00',
                        'score'      => [80 => 0, 230 => 0]
                    ], $game_arr));
                    // France vs Australia
                    CompetitionGame::create(array_merge([
                        'host_team'  => 80,
                        'guest_team' => 14,
                        'start_at'   => '2022-11-22 21:00:00',
                        'score'      => [80 => 0, 14 => 0]
                    ], $game_arr));
                    // Denmark vs Tunisia
                    CompetitionGame::create(array_merge([
                        'host_team'  => 62,
                        'guest_team' => 230,
                        'start_at'   => '2022-11-22 15:00:00',
                        'score'      => [62 => 0, 230 => 0]
                    ], $game_arr));
                    // Denmark vs Australia
                    CompetitionGame::create(array_merge([
                        'host_team'  => 62,
                        'guest_team' => 14,
                        'start_at'   => '2022-11-30 17:00:00',
                        'score'      => [62 => 0, 14 => 0]
                    ], $game_arr));
                    // Tunisia vs Australia
                    CompetitionGame::create(array_merge([
                        'host_team'  => 230,
                        'guest_team' => 14,
                        'start_at'   => '2022-11-26 12:00:00',
                        'score'      => [230 => 0, 14 => 0]
                    ], $game_arr));
                    break;
                case 4:
                    foreach ([59, 128, 72, 52] as $country_id) {
                        CompetitionTeam::create(array_merge([
                            'group_id'  => $group->id,
                            'entity_id' => $country_id
                        ], $team_arr));
                    }

                    // Germany vs Japan
                    CompetitionGame::create(array_merge([
                        'host_team'  => 59,
                        'guest_team' => 128,
                        'start_at'   => '2022-11-23 15:00:00',
                        'score'      => [59 => 0, 128 => 0]
                    ], $game_arr));
                    // Germany vs Spain
                    CompetitionGame::create(array_merge([
                        'host_team'  => 59,
                        'guest_team' => 72,
                        'start_at'   => '2022-11-27 21:00:00',
                        'score'      => [59 => 0, 72 => 0]
                    ], $game_arr));
                    // Germany vs Costa Rica
                    CompetitionGame::create(array_merge([
                        'host_team'  => 59,
                        'guest_team' => 52,
                        'start_at'   => '2022-12-01 21:00:00',
                        'score'      => [59 => 0, 52 => 0]
                    ], $game_arr));
                    // Japan vs Spain
                    CompetitionGame::create(array_merge([
                        'host_team'  => 128,
                        'guest_team' => 72,
                        'start_at'   => '2022-12-01 21:00:00',
                        'score'      => [128 => 0, 72 => 0]
                    ], $game_arr));
                    // Japan vs Costa Rica
                    CompetitionGame::create(array_merge([
                        'host_team'  => 128,
                        'guest_team' => 52,
                        'start_at'   => '2022-11-27 12:00:00',
                        'score'      => [128 => 0, 52 => 0]
                    ], $game_arr));
                    // Spain vs Costa Rica
                    CompetitionGame::create(array_merge([
                        'host_team'  => 72,
                        'guest_team' => 52,
                        'start_at'   => '2022-11-23 18:00:00',
                        'score'      => [72 => 0, 52 => 0]
                    ], $game_arr));
                    break;
                case 5:
                    foreach ([103, 143, 39, 21] as $country_id) {
                        CompetitionTeam::create(array_merge([
                            'group_id'  => $group->id,
                            'entity_id' => $country_id
                        ], $team_arr));
                    }

                    // Croatia vs Morocco
                    CompetitionGame::create(array_merge([
                        'host_team'  => 103,
                        'guest_team' => 143,
                        'start_at'   => '2022-11-23 12:00:00',
                        'score'      => [103 => 0, 143 => 0]
                    ], $game_arr));
                    // Croatia vs Canada
                    CompetitionGame::create(array_merge([
                        'host_team'  => 103,
                        'guest_team' => 39,
                        'start_at'   => '2022-11-27 18:00:00',
                        'score'      => [103 => 0, 39 => 0]
                    ], $game_arr));
                    // Croatia vs Belgium
                    CompetitionGame::create(array_merge([
                        'host_team'  => 103,
                        'guest_team' => 21,
                        'start_at'   => '2022-12-01 17:00:00',
                        'score'      => [103 => 0, 21 => 0]
                    ], $game_arr));
                    // Morocco vs Canada
                    CompetitionGame::create(array_merge([
                        'host_team'  => 143,
                        'guest_team' => 39,
                        'start_at'   => '2022-12-01 18:00:00',
                        'score'      => [143 => 0, 39 => 0]
                    ], $game_arr));
                    // Morocco vs Belgium
                    CompetitionGame::create(array_merge([
                        'host_team'  => 143,
                        'guest_team' => 21,
                        'start_at'   => '2022-11-27 15:00:00',
                        'score'      => [143 => 0, 21 => 0]
                    ], $game_arr));
                    // Canada vs Belgium
                    CompetitionGame::create(array_merge([
                        'host_team'  => 39,
                        'guest_team' => 21,
                        'start_at'   => '2022-11-23 21:00:00',
                        'score'      => [39 => 0, 21 => 0]
                    ], $game_arr));
                    break;
                case 6:
                    foreach ([196, 44, 32, 48] as $country_id) {
                        CompetitionTeam::create(array_merge([
                            'group_id'  => $group->id,
                            'entity_id' => $country_id
                        ], $team_arr));
                    }

                    // Serbia vs Switzerland
                    CompetitionGame::create(array_merge([
                        'host_team'  => 196,
                        'guest_team' => 44,
                        'start_at'   => '2022-11-23 12:00:00',
                        'score'      => [196 => 0, 44 => 0]
                    ], $game_arr));
                    // Serbia vs Brazil
                    CompetitionGame::create(array_merge([
                        'host_team'  => 196,
                        'guest_team' => 32,
                        'start_at'   => '2022-11-27 18:00:00',
                        'score'      => [196 => 0, 32 => 0]
                    ], $game_arr));
                    // Serbia vs Cameroon
                    CompetitionGame::create(array_merge([
                        'host_team'  => 196,
                        'guest_team' => 48,
                        'start_at'   => '2022-12-01 17:00:00',
                        'score'      => [196 => 0, 48 => 0]
                    ], $game_arr));
                    // Switzerland vs Brazil
                    CompetitionGame::create(array_merge([
                        'host_team'  => 44,
                        'guest_team' => 32,
                        'start_at'   => '2022-12-01 18:00:00',
                        'score'      => [44 => 0, 32 => 0]
                    ], $game_arr));
                    // Switzerland vs Cameroon
                    CompetitionGame::create(array_merge([
                        'host_team'  => 44,
                        'guest_team' => 48,
                        'start_at'   => '2022-11-27 15:00:00',
                        'score'      => [44 => 0, 48 => 0]
                    ], $game_arr));
                    // Brazil vs Cameroon
                    CompetitionGame::create(array_merge([
                        'host_team'  => 32,
                        'guest_team' => 48,
                        'start_at'   => '2022-11-23 21:00:00',
                        'score'      => [32 => 0, 48 => 0]
                    ], $game_arr));
                    break;
                case 7:
                    foreach ([128, 190, 87, 241] as $country_id) {
                        CompetitionTeam::create(array_merge([
                            'group_id'  => $group->id,
                            'entity_id' => $country_id
                        ], $team_arr));
                    }

                    // South Korea vs Portugal
                    CompetitionGame::create(array_merge([
                        'host_team'  => 128,
                        'guest_team' => 190,
                        'start_at'   => '2022-12-02 17:00:00',
                        'score'      => [128 => 0, 190 => 0]
                    ], $game_arr));
                    // South Korea vs Ghana
                    CompetitionGame::create(array_merge([
                        'host_team'  => 128,
                        'guest_team' => 87,
                        'start_at'   => '2022-11-28 15:00:00',
                        'score'      => [128 => 0, 87 => 0]
                    ], $game_arr));
                    // South Korea vs Uruguay
                    CompetitionGame::create(array_merge([
                        'host_team'  => 128,
                        'guest_team' => 241,
                        'start_at'   => '2022-11-24 15:00:00',
                        'score'      => [128 => 0, 241 => 0]
                    ], $game_arr));
                    // Portugal vs Ghana
                    CompetitionGame::create(array_merge([
                        'host_team'  => 190,
                        'guest_team' => 87,
                        'start_at'   => '2022-11-24 18:00:00',
                        'score'      => [190 => 0, 87 => 0]
                    ], $game_arr));
                    // Portugal vs Uruguay
                    CompetitionGame::create(array_merge([
                        'host_team'  => 190,
                        'guest_team' => 241,
                        'start_at'   => '2022-11-28 21:00:00',
                        'score'      => [190 => 0, 241 => 0]
                    ], $game_arr));
                    // Ghana vs Uruguay
                    CompetitionGame::create(array_merge([
                        'host_team'  => 87,
                        'guest_team' => 241,
                        'start_at'   => '2022-12-02 17:00:00',
                        'score'      => [87 => 0, 241 => 0]
                    ], $game_arr));
                    break;
            }
        }
    }
}
