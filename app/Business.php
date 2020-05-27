<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    public function images()
    {
        return $this->hasMany('App\Image');
    }

    public function ratings()
    {
        return $this->hasMany('App\Rating');
    }
    public function business_categories()
    {
        return $this->hasMany('App\BusinessCategory');
    }
}
