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
		return Redirect::to('/');
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
		$user = new \App\User;

		$user->firstname = Input::get('firstname');
		$user->lastname = Input::get('lastname');
		$user->email = Input::get('email');
		$user->password = Hash::make(Input::get('password'));

		$user->save();
		Auth::login($user);

		return Redirect::to('/home')
			->with('user', $user);
	}
	
});

Route::get('/home', function()
{
	if(Auth::check())
	{
		return View::make('home');	
	}
	else
	{
		return Redirect::to('/index');
	}
});

Route::get('/new-post-form', function()
{
	Return View::make('new_post_form');	
});

Route::post('/new-post-form', function()
{

	$user = Auth::user();
	
	$post = new Post;
	
	$post->title = Input::get('title');
	$post->description = Input::get('description');
	$post->file = Input::get('file');
	$post->user_id = $user->id;
	
	$post->save();
	
	return View::make('home');

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