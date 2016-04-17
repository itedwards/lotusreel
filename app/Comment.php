<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public function post(){
    	return $this->belongsTo('Post');
    }

    protected $fillable = array('comment', 'user_id', 'man_id');
}

