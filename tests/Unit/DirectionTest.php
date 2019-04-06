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

class DirectionTest extends TestCase
{
    use WithFaker, DatabaseTransactions;

    public function testUserCanCreateDirection()
    {
    	Passport::actingAs(
    		factory(User::class)->create(),
    		['create-recipe', 'edit-recipe', 'delete-recipe', 'create-direction']
    	);

    	$recipe = factory(Recipe::class)->create(['user_id' => Auth::id()]);

    	$direction = factory(Direction::class)->create([
    		'user_id' => Auth::id(),
    		'recipe_id' => $recipe->id,
    	]);

    	$this->post('/api/recipe/'.$recipe->id.'/direction', $direction->toArray())->assertStatus(201);
    }

    public function testUserCanUpdateDirection()
    {
    	Passport::actingAs(
    		factory(User::class)->create(),
    		['create-recipe', 'edit-recipe', 'delete-recipe', 'create-direction', 'edit-direction']
    	);

    	$recipe = factory(Recipe::class)->create(['user_id' => Auth::id()]);

    	$direction = factory(Direction::class)->create([
    		'user_id' => Auth::id(),
    		'recipe_id' => $recipe->id,
    	]);

    	$direction->step = 1;
    	$direction->direction = "Updated Direction";

    	$this->put('/api/recipe/'.$recipe->id.'/direction/'.$direction->id, $direction->toArray());

    	$this->assertDatabaseHas('directions', ['id' => $direction->id, 'step' => 1, 'direction' => 'Updated Direction']);
    }

    public function testUserCanDeleteDirection()
    {
    	Passport::actingAs(
    		factory(User::class)->create(),
    		['create-recipe', 'edit-recipe', 'delete-recipe', 'create-direction', 'edit-direction', 'delete-direction']
    	);

    	$recipe = factory(Recipe::class)->create(['user_id' => Auth::id()]);

    	$direction = factory(Direction::class)->create([
    		'user_id' => Auth::id(),
    		'recipe_id' => $recipe->id,
    	]);

    	$response = $this->delete('/api/recipe/'.$recipe->id.'/direction/'.$direction->id);

    	$response->assertOK();
    }
}
