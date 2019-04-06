<?php

use Illuminate\Support\Str;
use Faker\Generator as Faker;
use App\Recipe;
use App\User;

$factory->define(Recipe::class, function (Faker $faker) {
    return [
        'user_id' => User::all()->random(),
        'title' => $faker->sentence,
        'yeilds' => $faker->word,
        'prep_time' => $faker->name,
        'total_time' => $faker->name,
    ];
});
