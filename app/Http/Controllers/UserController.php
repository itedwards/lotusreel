<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\User;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
	// Upon accessing the main URI, checks if user has remember token
	public function checkRemember(){
		if($remember = true && Auth::check())
		{
			return Redirect::to('/home');
		
		}
		else
		{
			return View::make('index');
		}
	}

	// Validates user login attempt on landing page
	public function login(){
		$credentials = Input::only('email', 'password');
		
		if(Auth::attempt($credentials, $remember = true))
		{
			return Redirect::intended('/home');
		}
		else
		{
			$user = Auth::user();
			return Redirect::to('/')
				->with('user', $user);
		}
	}

	// User logout function
	public function logout(){

		Auth::logout();
		return Redirect::to('/');

	}

	// Handles sign up by adding profile images to Amazon s3 and putting all general information into database
	public function signUp(){
		$rules = array(
			'email' => 'email|unique:users,email',
			'password' => 'min:7'   
		);
	
		$validator = Validator::make(Input::all(), $rules);

		if($validator->fails()) {
		    return Redirect::to('/sign-up')
		        ->with('flash_message', 'Sign up failed; please fix the errors listed below.')
		        ->withInput()
		        ->withErrors($validator);
		}
		else
		{
			$profile = $_FILES['profile_photo'];
			$cover = $_FILES['cover_photo'];
			
			if(isset($profile) && isset($cover))
			{
				$user = new \App\User;	
				/*
				|--------------------------------------------------------------------------
				| Add Profile Photo
				|--------------------------------------------------------------------------
				*/
				$profile_name = $_FILES['profile_photo']['name'];
				$profile_tmp_name = $_FILES['profile_photo']['tmp_name'];
			
				$profile_extension = explode('.', $profile_name);
				$profile_extension = strtolower(end($profile_extension));
			
				$profile_key = md5(uniqid());
				$profile_tmp_file_name = "{$profile_key}.{$profile_extension}";
				$profile_tmp_file_path = "../files/{$profile_tmp_file_name}";
				
				move_uploaded_file($profile_tmp_name, $profile_tmp_file_path);
				
				$s3 = App::make('aws')->createClient('s3');
				$result = $s3->putObject(array(
			    	'Bucket'     => 'lotusreelmedia',
					'Key'        =>  "uploads/{$profile_name}",
					'SourceFile' =>  $profile_tmp_file_path,
					'ACL' => 'public-read'
				));
				
						
				$user->profile_photo = $s3->getObjectUrl('lotusreelmedia', "uploads/{$profile_name}");
				
				/*
				|--------------------------------------------------------------------------
				| Add Cover Photo
				|--------------------------------------------------------------------------
				*/
				
				$cover_name = $_FILES['cover_photo']['name'];
				$cover_tmp_name = $_FILES['cover_photo']['tmp_name'];
			
				$cover_extension = explode('.', $cover_name);
				$cover_extension = strtolower(end($cover_extension));
			
				$cover_key = md5(uniqid());
				$cover_tmp_file_name = "{$cover_key}.{$cover_extension}";
				$cover_tmp_file_path = "../files/{$cover_tmp_file_name}";
				
				move_uploaded_file($cover_tmp_name, $cover_tmp_file_path);
				
				$s3 = App::make('aws')->createClient('s3');
				$result = $s3->putObject(array(
			    	'Bucket'     => 'lotusreelmedia',
					'Key'        =>  "uploads/{$cover_name}",
					'SourceFile' =>  $profile_tmp_file_path,
					'ACL' => 'public-read'
				));
				
						
				$user->cover_photo = $s3->getObjectUrl('lotusreelmedia', "uploads/{$cover_name}");
			
				/*
				|--------------------------------------------------------------------------
				| Add Additional Info
				|--------------------------------------------------------------------------
				*/
				
				$user->firstname = Input::get('firstname');
				$user->lastname = Input::get('lastname');
				$user->bio = Input::get('bio');
				$user->followers = "";
				$user->followed = "";
				$user->email = Input::get('email');
				$user->password = Hash::make(Input::get('password'));
				$user->save();
				
				
				Auth::login($user);
		
				return Redirect::to('/home')
					->with('user', $user);
			}
			else
			{
				return Redirect::to('/sign-up');
			}
		}
	}

	public function addFollower($id){
		$user = Auth::user();
		if ($user['followed'] == "") {
			$following = [];
		}
		else{
			$following = unserialize($user['followed']);
		}

		array_push($following, $id);

		$following = serialize($following);

		DB::table('users')
			->where('id', $user['id'])
			->update(['followed' => $following]);

		return Redirect::to('/home');

	}
}