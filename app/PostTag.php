<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostTag extends Model
{
    public function post(){
    	return $this->belongsTo('App\Post');
    }

    public function tag(){
    	return $this->belongsTo('App\Tag');
    }
}
