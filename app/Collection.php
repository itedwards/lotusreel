<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    public function user()
	{
		return $this->belongsTo('User');
	}

	public function post(){
		return $this->hasMany('Post');
	}
	
	protected $fillable = array('collection_name', 'posts_in_collection');
}
