<?php

use Illuminate\Support\Str;
use Faker\Generator as Faker;
use App\Recipe;
use App\Rating;
use App\User;

$factory->define(Rating::class, function (Faker $faker) {
    return [
        'user_id' => User::all()->random(),
        'recipe_id' => Recipe::all()->random(),
        'rating' => $faker->numberBetween($min = 1, $max = 10),
        'comment' => $faker->sentence,
    ];
});
