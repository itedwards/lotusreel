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

Route::get('/', 'UserController@checkRemember');

Route::post('/', 'UserController@login');

Route::get('/logout', 'UserController@logout');

Route::get('sign-up', array('before' => 'guest', function()
{

	return View::make('sign-up');

}));

Route::post('sign-up', 'UserController@signUp');

Route::get('/home', 'PostController@getPosts');

Route::post('/home', 'PostController@postInteraction');

Route::get('/profile/{url_id}/{title?}', function($url_id, $title = null){

    $user_query = DB::table('users')
        ->where('url_id', '=', $url_id)
        ->get();

    $user = json_decode(json_encode($user_query[0]), true);

    $posts_query = DB::table('posts')->where('user_id', '=', $user['id'])->get();
    $collection_query = DB::table('collections')->where('user_id', '=', Auth::id())->get();

    if($title == null){
        $posts_query = DB::table('posts')->where('user_id', '=', $user['id'])->get();
        $collection_query = DB::table('collections')->where('user_id', '=', Auth::id())->get();

        return View::make('profile')
            ->with('posts_query', $posts_query)
            ->with('collection_query', $collection_query)
            ->with('user', $user);
    }
    else{

        $posts_query = DB::table('posts')
            ->where('user_id', '=', $user['id'])
            ->where('title', '=', $title)
            ->get();

        return View::make('post')
            ->with('posts_query', $posts_query)
            ->with('user', $user);
    }

});

Route::post('/profile/{url_id}', 'UserController@addFollower');

Route::get('/upload', function()
{
	if(Auth::check())
	{
        $collection_query = DB::table('collections')->where('user_id', '=', Auth::id())->get();
		Return View::make('new_post_form')
            ->with('collection_query', $collection_query);
	}
	else
	{
		return Redirect::to('/');
	}
});

Route::post('/upload', 'PostController@addPost');

Route::get('/explore', function(){

    return View::make('explore');

});

Route::get('/new-collection-form', function()
{
	if(Auth::check())
	{
		Return View::make('new_collection_form');
	}
	else
	{
		return Redirect::to('/');
	}
});

Route::post('/new-collection-form', 'CollectionController@addCollection');

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