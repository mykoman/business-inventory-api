<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    public function business()
    {
    	return $this->belongsTo('App\Business');
    }
}
