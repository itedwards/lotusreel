<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{

	public function postTag(){
    	return $this->belongsToMany('App\PostTag');
    }

}
