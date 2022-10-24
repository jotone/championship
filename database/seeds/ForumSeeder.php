<?php

use App\Models\ForumTopic;
use Illuminate\Database\Seeder;

class ForumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 0; $i < 25; $i++) {
            factory(ForumTopic::class)->create(['position' => $i]);
        }
    }
}