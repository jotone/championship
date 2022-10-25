<?php

use App\Models\{ForumMessage, ForumTopic};
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
        for($i = 0; $i < 8; $i++) {
            factory(ForumTopic::class)->create(['position' => $i]);
        }

        for($i = 0; $i < 250; $i++) {
            factory(ForumMessage::class)->create();
        }
    }
}