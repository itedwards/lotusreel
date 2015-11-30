<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use Illuminate\Http\Request;

Route::get('/', function()
{
	if($remember = true && Auth::check())
	{
		return Redirect::to('/home');
		
	}
	else
	{
		return View::make('index');
	}
});

Route::post('/', function()
{

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
	
});

Route::get('/logout', function()
{

	Auth::logout();

	return Redirect::to('/');

});

Route::get('sign-up', array('before' => 'guest', function()
{

	return View::make('sign-up');

}));

Route::post('sign-up', function()
{
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
	
});

Route::get('/home', function()
{
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
});

Route::get('/profile/{id}', function($id){
	return View::make('profile')
		->with('id', $id);
});

Route::get('/new-post-form', function()
{
	if(Auth::check())
	{
		Return View::make('new_post_form');
	}
	else
	{
		return Redirect::to('/');
	}
});

Route::post('/new-post-form', function()
{

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
		
		$s3 = App::make('aws')->createClient('s3');
		$result = $s3->putObject(array(
	    	'Bucket'     => 'lotusreelmedia',
			'Key'        =>  "uploads/{$name}",
			'SourceFile' =>  $tmp_file_path,
			'ACL' => 'public-read'
		));
		
				
		$post->file = $s3->getObjectUrl('lotusreelmedia', "uploads/{$name}");
		$post->user_id = $user->id;
		
		$post->save();
		
		unlink("../files/{$tmp_file_name}");
		
		return Redirect::to('/home');
	}
	else
	{
		return Redirect::to('/new-post-form');
	}

});

Route::get('/debug', function() {

    echo '<pre>';

    echo '<h1>environment.php</h1>';
    $path   = base_path().'/environment.php';

    try {
        $contents = 'Contents: '.File::getRequire($path);
        $exists = 'Yes';
    }
    catch (Exception $e) {
        $exists = 'No. Defaulting to `production`';
        $contents = '';
    }

    echo "Checking for: ".$path.'<br>';
    echo 'Exists: '.$exists.'<br>';
    echo $contents;
    echo '<br>';

    echo '<h1>Environment</h1>';
    echo App::environment().'</h1>';

    echo '<h1>Debugging?</h1>';
    if(Config::get('app.debug')) echo "Yes"; else echo "No";

    echo '<h1>Database Config</h1>';
    print_r(Config::get('database.connections.mysql'));

    echo '<h1>Test Database Connection</h1>';
    try {
        $results = DB::select('SHOW DATABASES;');
        echo '<strong style="background-color:green; padding:5px;">Connection confirmed</strong>';
        echo "<br><br>Your Databases:<br><br>";
        print_r($results);
    } 
    catch (Exception $e) {
        echo '<strong style="background-color:crimson; padding:5px;">Caught exception: ', $e->getMessage(), "</strong>\n";
    }

    echo '</pre>';

});