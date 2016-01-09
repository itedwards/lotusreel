<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

use App\Post;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
	// Gets all relevant posts for users main feed
	public function getPosts(){
		if(Auth::check())
		{
			$user = Auth::id();
			$posts_query = DB::table('posts')->where('user_id', '=', $user)->get();
			return View::make('home')
				->with('posts_query', $posts_query);
		}
		else
		{
			$user = Auth::user();
			return Redirect::to('/')
				->with('user', $user);
		}
	}

	// gets profile page of user with the requested id
	public function showProfile($id){
		return View::make('profile')
			->with('id', $id);
	}

	// Handles post additon by adding main file to Amazon s3 and putting all general information into database
	public function addPost(){
		$user = Auth::user();
	
		$file = $_FILES['file'];
			
		if(isset($file))
		{		
			$name = $_FILES['file']['name'];
			$tmp_name = $_FILES['file']['tmp_name'];
			
			$extension = explode('.', $name);
			$extension = strtolower(end($extension));
			
			$key = md5(uniqid());
			$tmp_file_name = "{$key}.{$extension}";
			$tmp_file_path = "../files/{$tmp_file_name}";
			
			move_uploaded_file($tmp_name, $tmp_file_path);
		
			$post = new \App\Post;
			
			$post->title = Input::get('title');
			$post->description = Input::get('description');
			
			$s3 = AWS::createClient('s3');
			$s3->putObject(array(
		    	'Bucket'     => 'lotusreelmedia',
				'Key'        =>  "uploads/{$name}",
				'SourceFile' =>  $tmp_file_path,
				'ACL' => 'public-read'
			));
			
					
			$post->file = $s3->getObjectUrl('lotusreelmedia', "uploads/{$name}");
			$post->file_type = $extension;
			$post->user_id = $user->id;
			
			$post->save();
			
			unlink("../files/{$tmp_file_name}");
			
			return Redirect::to('/home');
		}
		else
		{
			return Redirect::to('/new-post-form');
		}
	}
}