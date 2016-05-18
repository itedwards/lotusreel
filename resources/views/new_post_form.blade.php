@extends('master')

<?
	$user = Auth::user();

  $collections = array();
  for($i = 0; $i < sizeof(json_decode(json_encode($collection_query), true)); $i++){
    $collections[$i] = json_decode(json_encode($collection_query[$i]), true);
  }
?>

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

      <div class="col-lg-6">
        <div class="input-group">
          <form class="navbar-form navbar-left" role="search">
            <input type="text" class="form-control" placeholder="Search">
            <span class="input-group-btn">
              <button class="btn btn-default" type="button">Go!</button>
            </span>
          </form>
        </div><!-- /input-group -->
      </div><!-- /.col-lg-6 -->

      <ul class="nav navbar-nav navbar-right">
        <li class="active"><a href="/upload"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Upload</a></li>
        <li><a href="/logout">Log Out</a></li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
<br>
<br>
<br>
<div class="container">
  <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#newPost">New Post</a></li>
    <li><a data-toggle="tab" href="#newCollection">New Collection</a></li>
  </ul>
  <br>
  <div class="tab-content">
    <div id="newPost" class="tab-pane fade in active">
			<form action="/upload" method="post" enctype="multipart/form-data">
				<div class="form-group">
					<label for="title">Title</label>
					<input type="text" class="form-control" id="title" placeholder="Give Post a Title" name="title" id="title">
				</div>
				<div class="form-group">
					<label for="description" >Description</label>
					<textarea class="form-control" rows="3" placeholder="Give Post a Description" name="description" id="description"></textarea>
				</div>
				<div class="form-group">
					<label for="InputFile">Your Creation</label>
					<input type="file" id="InputFile" name="file">
					<p class="help-block">Make it anything.</p>
				</div>
        <div>
          <label for="collectionSelect">Collection</label>
          <select name="collection" class="custom-select custom-select-sm">
            <?php foreach($collections as $collection){ ?>
              <option value="<?php echo $collection['id']; ?>"><?php echo $collection['collection_name']; ?></option>
            <? } ?>
          </select>
        </div>
        <br>
				<button type="submit" class="btn btn-default">Submit</button>
			</form>
    </div>
    <div id="newCollection" class="tab-pane fade">
	    <form action="/new-collection-form" method="post" enctype="multipart/form-data">
	      <div class="form-group">
	        <label for="title">Title</label>

	        <input class="form-control" name="title" placeholder="Title" type="text">
	      </div>
	        <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
	      <div class="form-group">
	        <button type="submit" class="btn btn-default">Create</button>
	      </div>
	    </form>
	  </div>
  </div>
</div>
@stop