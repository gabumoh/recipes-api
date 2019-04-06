<?php

use Illuminate\Support\Str;
use Faker\Generator as Faker;
use App\Recipe;
use App\Direction;
use App\User;

$factory->define(Direction::class, function (Faker $faker) {
    return [
        'user_id' => User::all()->random(),
        'recipe_id' => Recipe::all()->random(),
        'step' => $faker->randomDigitNotNull,
        'direction' => $faker->sentence,
    ];
});
