<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Direction extends Model
{
    use SoftDeletes;

	/**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    protected $hidden = ['id', 'user_id', 'recipe_id', 'deleted_at', 'created_at', 'updated_at'];

    public function recipe()
    {
    	return $this->belongsTo(Recipe::class);
    }
}
