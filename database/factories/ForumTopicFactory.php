<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\{ForumTopic, Role, User};
use Faker\Generator as Faker;

$factory->define(ForumTopic::class, function (Faker $faker) {
    $name = $faker->colorName . ' ' . $faker->jobTitle();
    return [
        'name'        => $name,
        'url'         => generateUrl($name),
        'created_by'  => User::where('role_id', Role::where('slug', 'superadmin')->value('id'))->value('id'),
        'img_url'     => mt_rand(0, 1) ? $faker->imageUrl : null,
        'description' => $faker->realText(mt_rand(45, 100)),
        'text'        => '<p>' . $faker->realText(600) . '</p>',
        'pinned'      => mt_rand(0, 1),
        'position'    => 0
    ];
});