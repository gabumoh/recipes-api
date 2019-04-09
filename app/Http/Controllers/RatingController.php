<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Recipe;
use App\Rating;
use App\Http\Resources\RatingResource;
use Validator;
use Auth;

class RatingController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $recipe required passed within request
     * @param int rating required range between 1 and 10 (min:1, max:10)
     * @param text comment not-required
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Recipe $recipe)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'rating' => 'required|integer|between:1,10',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $rating = Rating::create([
            'user_id' => $request->user()->id,
            'recipe_id' => $recipe->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return new RatingResource($rating);
    }

    /**
     * Display the specified resource.
     *
     * @param int $rating required passed within request
     * @return \Illuminate\Http\Response
     */
    public function show(Rating $rating)
    {
        return new RatingResource($rating);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $recipe required passed within request
     * @param int $rating required passed within request
     * @param int rating required range between 1 and 10 (min:1, max:10)
     * @param text comment not-required
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Recipe $recipe, Rating $rating)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'rating' => 'required|integer|between:1,10', 
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        if ($request->user()->id !== $rating->user_id) {
            return response()->json(['error' => 'You can only edit your own reviews.'], 403);
        }

        $rating->update($request->only(['rating', 'comment']));

        return new RatingResource($rating);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $recipe required passed within request
     * @param int $rating required passed within request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Recipe $recipe, Rating $rating)
    {
        if (Auth::id() !== $rating->user_id) {
            return response()->json(['error' => 'You can only delete your own reviews.'], 403);
        }

        $rating->delete();

        return response()->json('Deleted Successfully', 200);
    }
}
