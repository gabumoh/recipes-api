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

class IngredientTest extends TestCase
{
    use WithFaker, DatabaseTransactions;

    public function testUserCanCreateIngredient()
    {
    	Passport::actingAs(
    		factory(User::class)->create(),
    		['create-recipe', 'edit-recipe', 'delete-recipe', 'create-ingredient']
    	);

    	$recipe = factory(Recipe::class)->create(['user_id' => Auth::id()]);

    	$ingredient = factory(Ingredient::class)->create([
    		'user_id' => Auth::id(),
    		'recipe_id' => $recipe->id,
    	]);

    	$this->post('/api/recipe/'.$recipe->id.'/ingredient', $ingredient->toArray())->assertStatus(201);
    }

    public function testUserCanUpdateIngredient()
    {
    	Passport::actingAs(
    		factory(User::class)->create(),
    		['create-recipe', 'edit-recipe', 'delete-recipe', 'create-ingredient', 'edit-ingredient']
    	);

    	$recipe = factory(Recipe::class)->create(['user_id' => Auth::id()]);

    	$ingredient = factory(Ingredient::class)->create([
    		'user_id' => Auth::id(),
    		'recipe_id' => $recipe->id,
    	]);

    	$ingredient->ingredient = "Updated Ingredient";

    	$this->put('/api/recipe/'.$recipe->id.'/ingredient/'.$ingredient->id, $ingredient->toArray());

    	$this->assertDatabaseHas('ingredients', ['id' => $ingredient->id, 'ingredient' => 'Updated Ingredient']);
    }

    public function testUserCanDeleteIngredient()
    {
    	Passport::actingAs(
    		factory(User::class)->create(),
    		['create-recipe', 'edit-recipe', 'delete-recipe', 'create-ingredient', 'edit-ingredient', 'delete-ingredient']
    	);

    	$recipe = factory(Recipe::class)->create(['user_id' => Auth::id()]);

    	$ingredient = factory(Ingredient::class)->create([
    		'user_id' => Auth::id(),
    		'recipe_id' => $recipe->id,
    	]);

    	$response = $this->delete('/api/recipe/'.$recipe->id.'/ingredient/'.$ingredient->id);

    	$response->assertOk();
    }
}
