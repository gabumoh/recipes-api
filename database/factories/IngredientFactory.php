<?php

use Illuminate\Support\Str;
use Faker\Generator as Faker;
use App\Recipe;
use App\Ingredient;
use App\User;

$factory->define(Ingredient::class, function (Faker $faker) {
    return [
        'user_id' => User::all()->random(),
        'recipe_id' => Recipe::all()->random(),
        'ingredient' => $faker->sentence,
    ];
});
