<?php

namespace Tests\Feature;

use App\User;
use App\Recipe;
use App\Ingredient;
use App\Direction;
use App\Rating;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;


class RecipeTest extends TestCase
{
    use DatabaseTransactions;
    
    protected $user;

    /**
     * Create a user and get token
     * @return string
     */

    protected function authenticate()
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@test.com',
            'password' => bcrypt('secret1234')
        ]);

        $this->user = $user;

        $token = $user->createToken('MyApp', ['create-recipe', 'edit-recipe', 'delete-recipe', 'create-ingredient', 'edit-ingredient', 'delete-ingredient', 'create-direction', 'edit-direction', 'delete-direction', 'add-review', 'edit-review', 'delete-review'])->accessToken;

        return $token;
    }

    public function testAll()
    {
        //Authenticate and attach recipe to the user
        $token = $this->authenticate();

        $recipe = Recipe::create([
            'user_id' => $this->user->id,
            'title' => 'Roast Turkey',
            'yeilds' => '10 servings',
            'prep_time' => '8 Hours 0 Mins',
            'total_time' => '12 Hours 30 Mins',
        ]);

        $this->user->recipes()->save($recipe);

        //Call Route and assert status
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->get('/api/recipes');
        $response->assertStatus(200);

        //Assert response contains Recipe with matching title        
        $this->assertEquals('Roast Turkey', $response->json()['data'][0]['title']);
    }

    public function testCreate()
    {
        //Get token
        $token = $this->authenticate();

        $recipe = Recipe::create([
            'user_id' => $this->user->id,
            'title' => 'Roast Turkey',
            'yeilds' => '10 servings',
            'prep_time' => '8 Hours 0 Mins',
            'total_time' => '12 Hours 30 Mins',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->post('/api/recipe', $recipe->toArray());

        //Assert Created Status
        $response->assertStatus(201);

        //Assert response contains Recipe with matching title
        $this->assertEquals('Roast Turkey', $response->json()['data']['title']);

        //Assert database has created book
        $this->assertDatabaseHas('recipes', [
            'id' => $recipe->id,
            'title' => 'Roast Turkey',
            'yeilds' => '10 servings',
            'prep_time' => '8 Hours 0 Mins',
            'total_time' => '12 Hours 30 Mins'
        ]);
    }
}
