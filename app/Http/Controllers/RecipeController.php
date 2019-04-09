<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Recipe;
use App\Http\Resources\RecipeResource;
use Validator;
use Auth;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return RecipeResource::collection(Recipe::with(['ingredients', 'directions', 'ratings'])->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param string title required
     * @param string yeilds required
     * @param string prep_time required
     * @param string total_time required
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => 'required',
            'yeilds' => 'required',
            'prep_time' => 'required',
            'total_time' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $recipe = Recipe::create([
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'yeilds' => $request->yeilds,
            'prep_time' => $request->prep_time,
            'total_time' => $request->total_time,
        ]);

        return new RecipeResource($recipe);
    }

    /**
     * Display the specified resource.
     *
     * @param int $recipe required passed withing request
     * @return \Illuminate\Http\Response
     */
    public function show(Recipe $recipe)
    {
        return new RecipeResource($recipe);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $recipe required passed within request
     * @param string title required
     * @param string yeild required
     * @param string prep_time required
     * @param string total_time required
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Recipe $recipe)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => 'required',
            'yeilds' => 'required',
            'prep_time' => 'required',
            'total_time' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        if ($request->user()->id !== $recipe->user_id) {
            return response()->json(['error' => 'You can only edit your own recipes.'], 403);
        }

        $recipe->update($request->only(['title', 'yeilds', 'prep_time', 'total_time']));

        return new RecipeResource($recipe);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $recipe required passed within request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Recipe $recipe)
    {
        if (Auth::id() !== $recipe->user_id) {
            return response()->json(['error' => 'You can only delete your own recipes.'], 403);
        }

        $recipe->delete();

        return response()->json('Deleted Successfully', 200);
    }
}
