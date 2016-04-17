<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use AWS;
use Illuminate\Support\Facades\View;

use App\Post;
use App\Http\Controllers\Controller;
use Request;

class PostController extends Controller
{
	// Gets all relevant posts for users main feed
	public function getPosts(){
		if(Auth::check())
		{
			$user = Auth::id();
			$posts_query = DB::table('posts')->where('user_id', '=', $user)->get();
			$collection_query = DB::table('collections')->where('user_id', '=', Auth::id())->get();
			return View::make('home')
				->with('posts_query', $posts_query)
				->with('collection_query', $collection_query);
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

			if(Input::get('collection') != null){
				$post->collection_id = Input::get('collection');
				DB::table('collections')
					->where('collection_name', Input::get('collection'))
					->increment('posts_in_collection');
			}
			else{
				$default_collection = DB::table('collections')
				->where('user_id', '=', Auth::id())
				->where('collection_name', '=', 'My Creations')
				->get();
				$post->collection_id = $default_collection;

				DB::table('collections')
					->where('collection_name', $default_collection)
					->increment('posts_in_collection');
			}


			$post->likes = "";
			$post->comments = "";

			$post->user_id = $user->id;
			
			$post->save();
			
			unlink("../files/{$tmp_file_name}");


			$url = 'http://gateway-a.watsonplatform.net/calls/text/TextGetRankedConcepts?';
			$apiKey = 'e5448b915d9a6ceea36018b4afb17c00ff905552';

			$url = $url .'apikey='.$apiKey;

			$titleText = urlencode(Input::get('title'));
			$descriptionText = urlencode(Input::get('description'));

			$url = $url . '&text=' . $titleText . $descriptionText;

			$url = $url . '&outputMode=json';

			$curl = curl_init();

			curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_URL => $url, 
				CURLOPT_POST => 1));

			// Send the request & save response to $resp
			$resp = (array) json_decode(curl_exec($curl));

			// Close request to clear up some resources
			curl_close($curl);
			
			return View::make('/test-watson-view')
				->with('resp', $resp);
		}
		else
		{
			return Redirect::to('/new-post-form');
		}
	}

	public function postInteraction() {

	    if(Request::ajax()) {

	    	$user = Auth::user();

	    	$currentPost = DB::table('posts')->where('id', '=', $_POST['post'])->get();
	    	$currentPost = json_decode(json_encode($currentPost[0]), true);

	    	if($_POST['type'] == 'comment'){

	    		$body = $_POST['input'];

	    		$commenter = $user->id;

	    		$comment = new \App\Comment;

	    		$comment->man_id = $target = uniqid();

	    		$comment->comment = $body;
	    		$comment->user_id = $commenter;
	    		$comment->post_id = $currentPost['id'];

	    		$comment->save();

	    		$currentComment = DB::table('comments')->where('man_id', '=', $target)->get();
	    		$currentComment = json_decode(json_encode($currentComment[0]), true);

	    		if ($currentPost['comments'] == "") {
					$comments = [];
				}
				else{
					$comments = unserialize($currentPost['comments']);
				}

				array_push($comments, $currentComment['id']);

				$comments = serialize($comments);

				DB::table('posts')
					->where('id', $currentPost['id'])
					->update(['comments' => $comments]);

				$results = View::make('comment_result')->with('comment', $currentComment)->render();

				return $results;
	    	}
	    	else if($_POST['type'] == 'like'){

				if ($currentPost['likes'] == "") {
					$likes = [];
				}
				else{
					$likes = unserialize($currentPost['likes']);
				}

				$totLikes;

				for($i = 0; $i < sizeof($likes); $i++){
					if($likes[$i] == $user['id']){
						$totLikes = sizeof($likes);
						return $totLikes;
					}
				}

				array_push($likes, $user['id']);

				$totLikes = sizeof($likes);

				$likes = serialize($likes);

				DB::table('posts')
					->where('id', $currentPost['id'])
					->update(['likes' => $likes]);

				return $totLikes;
	    	}
	    	else if($_POST['type'] == 'unlike'){
	    		$likes = unserialize($currentPost['likes']);

	    		$totLikes = sizeof($likes);

	    		if(sizeof($likes) == 1){
	    			$likes = '';
	    			$totLikes = 0;
	    		}
	    		else{

	    			for($i = 0; $i < sizeof($likes); $i++){
						if($likes[$i] == $user['id']){
							unset($likes[$i]);
							break;
						}
					}

					$likes = array_values($likes);
					$likes = serialize($likes);
					$totLikes = sizeof($likes);
	    		}


				DB::table('posts')
					->where('id', $currentPost['id'])
					->update(['likes' => $likes]);
						
				return $totLikes;

	    	}
	    }
	}
}