<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\{Competition, CompetitionGroup, Team, User};
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

        $competition = Competition::factory()->create();
        $n = $competition->groups_number + 65;
        for ($i = 65; $i < $n; $i++) {
            CompetitionGroup::create([
                'name'           => chr($i),
                'competition_id' => $competition->id
            ]);
        }
    }
}
