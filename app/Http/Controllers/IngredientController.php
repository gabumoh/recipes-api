<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Recipe;
use App\Ingredient;
use App\Http\Resources\IngredientResource;
use Validator;

class IngredientController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Recipe $recipe)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'ingredient' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $ingredient = Ingredient::create([
            'user_id' => $request->user()->id,
            'recipe_id' => $recipe->id,
            'ingredient' => $request->ingredient,
        ]);

        return new IngredientResource($ingredient);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $ingredient
     * @return \Illuminate\Http\Response
     */
    public function show(Ingredient $ingredient)
    {
        return new IngredientResource($ingredient);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $ingredient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Recipe $recipe, Ingredient $ingredient)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'ingredient' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        if ($request->user()->id !== $ingredient->user_id) {
            return response()->json(['error' => 'You can only edit your own ingredients.'], 403);
        }

        $ingredient->update($request->only('ingredient'));

        return new IngredientResource($ingredient);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ingredient $ingredient)
    {
        if ($request->user()->id !== $ingredient->user_id) {
            return response()->json(['error' => 'You can only delete your own ingredients.'], 403);
        }

        $ingredient->delete();

        return response()->json('Deleted Successfully', 200);
    }
}
