<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    public function business()
    {
    	return $this->belongsTo('App\Business');
    }
}
