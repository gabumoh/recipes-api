<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::tokensCan([
            'create-recipe' => 'can create recipe',
            'edit-recipe' => 'edit a reciep',
            'delete-recipe' => 'delete a recipe',
            'create-ingredient' => 'can create an ingredient',
            'edit-ingredient' => 'edit an ingredient',
            'delete-ingredient' => 'delete an ingredient',
            'create-direction' => 'can create a direction',
            'edit-direction' => 'can edit a direction',
            'delete-direction' => 'can delete a direction',
            'add-review' => 'can add a review',
            'edit-review' => 'can edit a review',
            'delete-review' => 'can delete a review',
        ]);

        Passport::routes();
    }
}
