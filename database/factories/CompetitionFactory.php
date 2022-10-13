<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Competition;
use Faker\Generator as Faker;

$factory->define(Competition::class, function (Faker $faker) {
    $name = $faker->company() . ' ' . uniqid();

    return [
        'name'          => $name,
        'slug'          => generateUrl($name),
        'groups_number' => mt_rand(1, 24),
        'img_url'       => $faker->imageUrl(),
        'bg_color'      => $faker->hexColor,
        'text_color'    => $faker->hexColor,
        'start_at'      => now(),
        'finish_at'     => now()->addMonths(mt_rand(1, 3)),
    ];
});
