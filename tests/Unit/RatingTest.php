<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use App\User;
use App\Recipe;
use App\Ingredient;
use App\Direction;
use App\Rating;
use Auth;

class RatingTest extends TestCase
{
    use WithFaker, DatabaseTransactions;

    public function testUserCanCreatingRating()
    {
    	Passport::actingAs(
    		factory(User::class)->create(),
    		['create-recipe', 'edit-recipe', 'delete-recipe', 'add-review']
    	);

    	$recipe = factory(Recipe::class)->create(['user_id' => Auth::id()]);

    	$rating = factory(Rating::class)->create([
    		'user_id' => Auth::id(),
    		'recipe_id' => $recipe->id,
    	]);

    	$this->post('/api/recipe/'.$recipe->id.'/rating', $rating->toArray())->assertStatus(201);
    }

    public function testUserCanUpdateRating()
    {
    	Passport::actingAs(
    		factory(User::class)->create(),
    		['create-recipe', 'edit-recipe', 'delete-recipe', 'add-review', 'edit-review']
    	);

    	$recipe = factory(Recipe::class)->create(['user_id' => Auth::id()]);

    	$rating = factory(Rating::class)->create([
    		'user_id' => Auth::id(),
    		'recipe_id' => $recipe->id,
    	]);

    	$rating->rating = 7;
    	$rating->comment = "Updated Comment";

    	$this->patch('/api/recipe/'.$recipe->id.'/rating/'.$rating->id, $rating->toArray());

    	$this->assertDatabaseHas('ratings', ['id' => $rating->id, 'rating' => 7, 'comment' => 'Updated Comment']);
    }

    public function testUserCanDeleteRating()
    {
    	Passport::actingAs(
    		factory(User::class)->create(),
    		['create-recipe', 'edit-recipe', 'delete-recipe', 'add-review', 'edit-review', 'delete-review']
    	);

    	$recipe = factory(Recipe::class)->create(['user_id' => Auth::id()]);

    	$rating = factory(Rating::class)->create([
    		'user_id' => Auth::id(),
    		'recipe_id' => $recipe->id,
    	]);

    	$this->delete('/api/recipe/'.$recipe->id.'/rating/'.$rating->id)->assertOk();
    }
}
