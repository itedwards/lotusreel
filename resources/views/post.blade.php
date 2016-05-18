@extends('master')

<?
		
	$post = json_decode(json_encode($posts_query[0]), true);

	$cover_photo = $post['file'];

	$allComments = DB::table('comments')->where('post_id', '=', $post['id'])->get();
    $allComments = json_decode(json_encode($allComments), true);

    $user_query = DB::table('users')->where('id', '=', $post['user_id'])->get();
	$post_user = json_decode(json_encode($user_query[0]), true);
?>

@section('head')
	<link rel="import" href="/bower_components/paper-toolbar/paper-toolbar.html">
	<link rel="import" href="/bower_components/paper-icon-button/paper-icon-button.html">
	<link rel="import" href="/bower_components/iron-icons/iron-icons.html">
	<link rel="import" href="/bower_components/iron-icons/maps-icons.html">
	<link rel="import" href="/bower_components/paper-input/paper-input.html">


	<style>
	   .intro-header {
		    padding-top: 0px; /* If you're making other pages, make sure there is 50px of padding to make sure the navbar doesn't overlap content! */
		    padding-bottom: 0px;
		    text-align: center;
		    color: #f8f8f8;
		    background: url(<? echo $cover_photo; ?>) no-repeat center center fixed;
		    background-size: cover;
	    }

	    .main-image{
	    	padding:20px;
	    	width: 100%;
	    	margin: auto;
	    	border: solid 3px #fff;
    		box-shadow: 0 0 3px 2px #ccc;
    		border-radius: 10px;
	    }

	    .intro-divider {
    		width: 400px;
    		border-top: 1px solid #f8f8f8;
    		border-bottom: 1px solid rgba(0,0,0,0.2);
		}
    </style>

@stop

@section('content')

<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="/" style="font-family: 'Playball', cursive;">LotusReel</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
          <li><a href="/"><span class="glyphicon glyphicon-list" aria-hidden="true"></span> My Reel</a></li>
          <li><a href="/profile/<? echo $user['url_id']; ?>"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> My Portfolio</a></li>
          <li><a href="/explore"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> Explore</a></li>
        </ul>
      <form class="navbar-form navbar-left" role="search">
        <div class="form-group">
            <input type="text" class="form-control" placeholder="Search">
        </div>
        <button type="submit" class="btn btn-default">Search</button>
      </form>

      <ul class="nav navbar-nav navbar-right">
        <li><a href="/upload"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Upload</a></li>
        <li><a href="/logout">Log Out</a></li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
<br>
<br>
	<!--<div class="intro-header">
		<div class="container">
	    	<div class="row">
		        <div class="col-lg-12">
		            <div class="intro-message">
		                
		            </div>
		        </div>
	    	</div>
		</div>
	</div> ---->

	<div style="padding:20px;">
		<center>
			<img src="<? echo $post['file']; ?>" align="middle" class="main-image">
		</center>

		<h1 align="center"><? echo $post['title']; ?> by <? echo $user['firstname'] ?> <? echo $user['lastname'] ?></h1>
		<hr class="intro-divider">


		<div class="col-md-8">
			<h2>Comments</h2>
			<ul class="list-group media-list media-list-stream">
				<li class="media list-group-item p-a">
					<div id="comments">
			            <ul class="media-list m-b">

			          		<li class="media" id="commentArea">
					            <a class="media-left" href="#">
					              <img class="media-object img-circle" src="<? echo $post_user['profile_photo']; ?>">
					            </a>
			            		<div class="media-body">
			              			<strong><? echo $user['firstname']; echo " ".$user['lastname'].": "; ?></strong>
			              			<input type="text" class="form-control commentField" placeholder="Comment" name="comment" id="<? echo 'comment-'.$post['id']; ?>">
			            		</div>
			         		</li>

			         		<hr>

			         		<?
			         			$allComments = array_reverse($allComments);
				            	foreach($allComments as $comment){

				            		$comUser = DB::table('users')->where('id', '=', $comment['user_id'])->get();
				            		$comUser = json_decode(json_encode($comUser[0]), true);
				            ?>
						            <li class="media">
						              <a class="media-left" href="#">
						                <img class="media-object img-circle" src="<? echo $comUser['profile_photo']; ?>">
						              </a>
						              <div class="media-body">
						                <strong><? echo $comUser['firstname']." ". $comUser['lastname'].":"; ?></strong><br>
						                <? echo $comment['comment']; ?>
						              </div>
						            </li>
				          	<?
				            	}
				          	?>
			        	</ul>
			      	</div>
			    </li>
			</ul>
		</div>
	</div>

	<script src="assets/js/post_interaction.js"></script>

@stop