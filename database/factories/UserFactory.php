<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\{Role, User};
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(User::class, function (Faker $faker) {
    return [
        'name'              => $faker->name,
        'email'             => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password'          => '123456',
        'img_url'           => $faker->imageUrl,
        'remember_token'    => Str::random(10),
        'role_id'           => Role::where('slug', 'regular')->value('id'),
        'info'              => mt_rand(0, 1) ? $faker->jobTitle . ' в ' . $faker->company : null,
    ];
});
