<?php

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
         $this->call(UserSeeder::class);
//         $this->call(TeamSeeder::class);
         $this->call(CompetitionSeeder::class);
         /*$this->call(UserFormSeeder::class);
         $this->call(ForumSeeder::class);*/
    }
}
