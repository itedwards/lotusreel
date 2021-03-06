@extends('master')

<?
	$user = Auth::user();

  $collections = array();
  for($i = 0; $i < sizeof(json_decode(json_encode($collection_query), true)); $i++){
    $collections[$i] = json_decode(json_encode($collection_query[$i]), true);
  }
?>

@section('head')
	<link rel="stylesheet" href="/css/bootstrap-tagsinput.css">

	<style>
    input[type="file"] {
      display: none;
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
        <li><a href="/<? echo $user['url_id']; ?>"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> My Portfolio</a></li>
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
	<form action="/upload" method="post" enctype="multipart/form-data">
		<div class="well" style="width: 75%; margin: auto;">
	  	<h1 align="center" style="font-family: 'Playball', cursive;">Add your Creation to LotusReel</h1>
	  	<br>
	  	<center>
		  	<label class="btn btn-lg btn-pill btn-info" style="vertical-align:middle;">
	    		<input name="file" type="file"/>
	    			<span class="glyphicon glyphicon-upload" aria-hidden="true"></span> Upload
				</label>
			</center>
	  </div>
	  <br>
	  <div class="well" style="width: 75%; margin: auto; padding:20px">

	  	<div class="form-group">
				<label for="title">Title</label>
				<input style="width:30%;" type="text" class="form-control" id="title" placeholder="Give Post a Title" name="title" id="title">
			</div>

			<div class="form-group">
				<label for="description">Description</label>
				<textarea class="form-control" rows="5" placeholder="Give Post a Description" name="description" id="description"></textarea>
			</div>

			<div style="display:inline-block">
        <label for="collectionSelect">Collection</label>
        <select name="collection" class="custom-select custom-select-sm">
          <?php foreach($collections as $collection){ ?>
            <option value="<?php echo $collection['id']; ?>"><?php echo $collection['collection_name']; ?></option>
          <? } ?>
        </select>
        <label for="tags">Tags</label>
        <input type="text" id="tags" name="tags" data-role="tagsinput">
      </div>
	  </div>
	  <br>
	  <center>
	  	<button class="btn btn-lg btn-pill btn-info" type="submit">
    		<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add
    	</button>
		</center>
	</form>
</div>
<script src="assets/js/bootstrap-tagsinput.js"></script>
<script src="assets/js/bootstrap-tagsinput-angular.js"></script>
<script>
	$('#tags').tagsinput({
  	maxTags: 5
	});
</script>
@stop