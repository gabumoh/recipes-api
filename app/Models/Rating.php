<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rating extends Model
{
	use SoftDeletes;

	/**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    public function recipe()
    {
    	return $this->belongsTo(Recipe::class);
    }
}
