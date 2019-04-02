<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recipe extends Model
{
	use SoftDeletes;

	/**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function ingredients()
    {
    	return $this->hasMany(Ingredient::class);
    }

    public function directions()
    {
    	return $this->hasMany(Direction::class);
    }

    public function ratings()
    {
    	return $this->hasMany(Rating::class);
    }
}
