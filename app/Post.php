<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function user()
	{
		return $this->belongsTo('User');
	}
	
	protected $fillable = array('title', 'description', 'file');
}
