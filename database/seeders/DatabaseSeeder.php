<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
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
        $users = User::factory(5)->create();
        $this->call(TeamSeeder::class);
        $this->call(CompetitionSeeder::class);
        $this->call(UserFormSeeder::class, false, ['users' => $users]);
    }
}
