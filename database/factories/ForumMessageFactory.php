<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\{ForumMessage, ForumTopic, User};
use Faker\Generator as Faker;

$factory->define(ForumMessage::class, function (Faker $faker) {
    $topic = ForumTopic::inRandomOrder()->first();

    $parent = mt_rand(0, 1)
        ? ForumMessage::where('topic_id', $topic->id)->inRandomOrder()->first()
        : null;

    $user = User::inRandomOrder()->first();

    return [
        'topic_id'  => $topic->id,
        'author_id' => $user->id,
        'parent_id' => $parent->id ?? null,
        'message'   => '<p>' . $faker->realText(mt_rand(45, 100)) . '</p>',
        'pinned'    => mt_rand(0, 10) > 9
    ];
});