<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\{Country, Team};
use Faker\Generator as Faker;

$factory->define(Team::class, function (Faker $faker) {
    $country = Country::inRandomOrder()->first();
    $name = $faker->country . ' ' . $faker->company;
    return [
        'en'         => $name,
        'ua'         => $name,
        'country_id' => $country->id,
        'img_url'    => $faker->imageUrl()
    ];
});
