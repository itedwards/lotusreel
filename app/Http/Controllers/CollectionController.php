<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use AWS;
use Illuminate\Support\Facades\View;

use App\Collection;
use App\Http\Controllers\Controller;

class CollectionController extends Controller
{
	public function addCollection(){
		$user = Auth::user();

		$collection = new \App\Collection;

		$collection->collection_name = Input::get('title');
		$collection->posts_in_collection = 0;
		$collection->user_id = $user->id;

		$collection->save();

		return Redirect::to('/home');
	}
}

