<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    public function user(){
    	$this->belongsTo('App\User');
    }
}
