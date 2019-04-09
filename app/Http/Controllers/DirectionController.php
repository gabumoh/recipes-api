<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Recipe;
use App\Direction;
use App\Http\Resources\DirectionResource;
use Validator;
use Auth;

class DirectionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $recipe required passed in request
     * @param int step required
     * @param text direction required
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Recipe $recipe)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'step' => 'required|integer',
            'direction' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $direction = Direction::create([
            'user_id' => $request->user()->id,
            'recipe_id' => $recipe->id,
            'step' => $request->step,
            'direction' => $request->direction,
        ]);

        return new DirectionResource($direction);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $direction required passed in request
     * @return \Illuminate\Http\Response
     */
    public function show(Direction $direction)
    {
        return new DirectionResource($direction);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $recipe required passed in request
     * @param int $direction required passed in request
     * @param int step required
     * @param text direction required  
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Recipe $recipe, Direction $direction)
    {
        $input = $request->all();

        $validator = Validator::make($input,[
            'step' => 'required|integer',
            'direction' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        if ($request->user()->id !== $direction->user_id) {
            return response()->json(['error' => 'You can only edit your own directions.'], 403);
        }

        $direction->update($request->only(['step', 'direction']));

        return new DirectionResource($direction);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $recipe required passed in request
     * @param int $direction required passed in request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Recipe $recipe, Direction $direction)
    {
        if (Auth::id() !== $direction->user_id) {
            return response()->json(['error' => 'You can only delete your own directions.'], 403);
        }

        $direction->delete();

        return response()->json('Deleted Successfully', 200);
    }
}
