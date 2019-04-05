<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Auth Routes
Route::post('login', 'AuthController@login');
Route::post('register', 'AuthController@register');

//Route for Admin Permissions
//Remember to secure route later
Route::prefix('admin')->group(function(){
	Route::post('login', 'AuthController@adminLogin');
	Route::post('register', 'AuthController@adminRegister');
});

// Recipes Endpoints
Route::get('recipes', 'RecipeController@index');
Route::get('recipes/{recipe}', 'RecipeController@show');
Route::post('recipe', 'RecipeController@store')->middleware(['auth:api', 'scope:create-recipe']);
Route::put('recipe/{recipe}', 'RecipeController@update')->middleware(['auth:api', 'scope:edit-recipe']);
Route::delete('recipe/{recipe}', 'RecipeController@destroy')->middleware(['auth:api', 'scope:delete-recipe']);

// Ingredients Endpoints
Route::get('recipe/{recipe}/ingredient/{ingredient}', 'IngredientController@show');
Route::post('recipe/{recipe}/ingredient', 'IngredientController@store')->middleware(['auth:api', 'scope:create-ingredient']);
Route::put('recipe/{recipe}/ingredient/{ingredient}', 'IngredientController@update')->middleware(['auth:api', 'scope:edit-ingredient']);
Route::delete('recipe/{recipe}/ingredient/{ingredient}', 'IngredientController@destroy')->middleware(['auth:api', 'scope:delete-ingredient']);

// Directions Endpoints
Route::get('recipe/{recipe}/direction/{direction}', 'DirectionController@show');
Route::post('recipe/{recipe}/direction', 'DirectionController@store')->middleware(['auth:api', 'scope:create-direction']);
Route::put('recipe/{recipe}/direction/{direction}', 'DirectionController@update')->middleware(['auth:api', 'scope:edit-direction']);
Route::delete('recipe/{recipe}/direction/{direction}', 'DirectionController@destroy')->middleware(['auth:api', 'scope:delete-direction']);

// Ratings Endpoints
Route::get('recipe/{recipe}/rating/{rating}', 'RatingController@show');
Route::post('recipe/{recipe}/rating', 'RatingController@store')->middleware(['auth:api', 'scope:add-review']);
Route::patch('recipe/{recipe}/rating/{rating}', 'RatingController@update')->middleware(['auth:api', 'scope:edit-review']);
Route::delete('recipe/{recipe}/rating/{rating}', 'RatingController@destroy')->middleware(['auth:api', 'scope:delete-review']);