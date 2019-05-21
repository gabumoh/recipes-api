<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthTest extends TestCase
{
    use DatabaseTransactions;
    
    /**
     * @test
     * Test registration
     */

     public function testRegister()
     {
        //User's data
        $user = [
            'name' => 'John Doe',
            'email' => 'john@test.com',
            'password' => 'secret1234',
            'c_password' => 'secret1234'
        ];

        //Send post request
        $response = $this->post('/api/register', $user);

        //Assert it was successful
        $response->assertStatus(200);

        //Assert we received a token
        $this->assertArrayHasKey('token', $response->json()['success']);
     }

     /**
      * @test
      * Test Login
      */
     public function testLogin()
     {
        User::create([
            'name' => 'John Doe',
            'email' => 'john@test.com',
            'password' => bcrypt('secret1234')
        ]);

        //Attempt Login
        $response = $this->post('/api/login', [
            'email' => 'john@test.com',
            'password' => 'secret1234'
        ]);

        //Assert it was successful
        $response->assertStatus(200);

        //Assert we received a token
        $this->assertArrayHasKey('token', $response->json()['success']);
     }
}
