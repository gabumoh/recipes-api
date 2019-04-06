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

class RecipeTest extends TestCase
{
	use WithFaker, DatabaseTransactions;

    public function testUserCanCreateRecipe()
    {
    	Passport::actingAs(
    		factory(User::class)->create(),
    		['create-recipe']
    	);

    	$recipe = factory(Recipe::class)->create();

    	$response = $this->post('/api/recipe', $recipe->toArray());

    	$response->assertStatus(201);
    }

    public function testUserCanUpdateRecipe()
    {
    	Passport::actingAs(
    		factory(User::class)->create(),
    		['create-recipe', 'edit-recipe']
    	);

    	$recipe = factory(Recipe::class)->create(['user_id' => Auth::id()]);

    	$recipe->title = "Updated Title";

    	$this->put('/api/recipe/'.$recipe->id, $recipe->toArray());

    	$this->assertDatabaseHas('recipes', ['id' => $recipe->id, 'title' => 'Updated Title']);
    }

    public function testUserCanDeleteRecipe()
    {
    	Passport::actingAs(
    		factory(User::class)->create(),
    		['create-recipe', 'edit-recipe', 'delete-recipe']
    	);

    	$recipe = factory(Recipe::class)->create(['user_id' => Auth::id()]);

    	$response = $this->delete('/api/recipe/'.$recipe->id);

    	$response->assertOK();
    }
}
