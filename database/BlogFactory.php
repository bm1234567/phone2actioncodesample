<?php

use Faker\Generator as Faker;

$factory->define(App\Blog::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(),
        'description' => $faker->paragraph(6),
        'author' => $faker->name(),
        'created_at' => $faker->dateTime(),
        'updated_at' => null
    ];
});
