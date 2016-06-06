@extends('master')

@section('title')
	
@stop

<?php
  $currUser = Auth::user();

  $cover_photo = $user['cover_photo'];

  $totPosts = 0;
  $new_array = array();
  for($i = 0; $i < sizeof(json_decode(json_encode($posts_query), true)); $i++){
    $new_array[$i] = json_decode(json_encode($posts_query[$i]), true);
    $totPosts++;
  }

  $collections = array();
  for($i = 0; $i < sizeof(json_decode(json_encode($collection_query), true)); $i++){
    $collections[$i] = json_decode(json_encode($collection_query[$i]), true);
  }

?>

@section('head')
  <link rel="import" href="/bower_components/paper-toolbar/paper-toolbar.html">
  <link rel="import" href="/bower_components/paper-icon-button/paper-icon-button.html">
  <link rel="import" href="/bower_components/paper-header-panel/paper-header-panel.html">
  <link rel="import" href="/bower_components/paper-card/paper-card.html">
  <link rel="import" href="/bower_components/paper-button/paper-button.html">
  <link rel="import" href="/bower_components/iron-icons/iron-icons.html">
  <link rel="import" href="/bower_components/iron-image/iron-image.html">
  <link rel="import" href="/bower_components/paper-tabs/paper-tabs.html">
  <link rel="import" href="/bower_components/iron-menu-behavior/iron-menu-behavior.html">
  <link rel="import" href="/bower_components/iron-fit-behavior/iron-fit-behavior.html">
  <link rel="import" href="/bower_components/iron-pages/iron-pages.html">
  <link rel="stylesheet" href="/css/profile.css">
  <script src="../assets/js/mason.js"></script>

  <style is="custom-style">
    .intro-header {
      padding-top: 0px; /* If you're making other pages, make sure there is 50px of padding to make sure the navbar doesn't overlap content! */
      padding-bottom: 0px;
      text-align: center;
      color: #f8f8f8;
      background: url(<? echo $cover_photo; ?>) no-repeat center center fixed;
      background-size: cover;
    }

   .free-wall {
      margin: 15px;
    }
    .brick {
      width: 320px;
      background: white;
      box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.33);
      border-radius: 3px;
      color: #333;
      border: none;
    }
    .info {
      padding: 15px;
      color: #333;
    }
    .brick img {
      margin: 0px;
      padding: 0px;
      display: block;
      width: 100%;
      max-width: 100%;
      display: block;
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
          <li class="active"><a href="/profile/<? echo $user['url_id']; ?>"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> My Portfolio <span class="sr-only">(current)</span></a></li>
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


<div class="intro-header">
  <div class="container">
      <div class="row">
          <div class="col-lg-12">
              <div class="intro-message">
                  <img src="<? echo $user['profile_photo']; ?>" id="profile-photo">
                  <h1><? echo $user['firstname']; echo " ".$user['lastname']; ?></h1>
                  <h3><? echo $user['bio']; ?></h3>
                  <hr class="intro-divider">
              </div>
          </div>
      </div>
  </div>
</div>

<div style="width:70%; display:inline-block; border-style: solid; border-width: 2px;">
  <paper-tabs selected="0">
    <paper-tab>My Portfolio</paper-tab>
    <paper-tab>My Information</paper-tab>
    <paper-tab>Shared</paper-tab>
  </paper-tabs>
</div>

<?
  if($user['id'] != Auth::id()){ ?>
    <div style="width:30%; display:inline-block; float:right; padding:5px; border-style: solid; border-width: 2px;">
  <?
      $isFollowing = false;
      $currFollows = unserialize($currUser['following']);
      for($i = 0; $i < sizeof($currFollows); $i++){
        if($currFollows[$i] == $user['id']){
          $isFollowing = true;
        }
      }

      if($isFollowing == false){
  ?>
        <form action="/<? echo $user['url_id']?>" method="post">
          <button type="submit" class="btn btn-primary-outline" style="float:right; padding: 5px;">
            <span class="icon icon-add-user"></span> Follow
          </button>
        </form>
  <?
      }
      else{
  ?>
        <form action="/<? echo $user['url_id']?>" method="post">
          <button type="submit" class="btn btn-primary" style="float:right; padding: 5px;">
            <span class="icon icon-add-user"></span> Following!
          </button>
        </form>
  <?
      }
    }
  ?>
</div>
<hr>
<iron-pages selected="0">
  
  <div class="tab-content" id="grid">
    <div id="freewall" class="free-wall">
        <?php 
          $new_array = array_reverse($new_array);
          foreach($new_array as $post){
        ?>
          <div class="brick">
            <img href="/<? echo $user['url_id']; ?>/<? echo $post['title']; ?>" src="<? echo $post['file']; ?>" width="100%">
            <div class="info">
                <h3><a href="/<? echo $user['url_id']; ?>/<? echo $post['title']; ?>"><? echo $post['title']; ?></a></h3>
                <h5><? echo $post['description']; ?></h5>
            </div>
        </div>
        <?
          }
        ?>
    </div>
  </div>

  <div class="tab-content">
    <h1>Followers</h1>
    <h1>Following</h1>
  </div>

  <div class="tab-content">
    
  </div>

</iron-pages>

<script src="../assets/js/jquery.min.js"></script>
<script src="../assets/js/chart.js"></script>
<script src="../assets/js/toolkit.js"></script>
<script src="../assets/js/application.js"></script>
<script>
  // execute/clear BS loaders for docs
  $(function(){
    if (window.BS&&window.BS.loader&&window.BS.loader.length) {
      while(BS.loader.length){(BS.loader.pop())()}
    }
  })

  var pages = document.querySelector('iron-pages');
     var tabs = document.querySelector('paper-tabs');

    tabs.addEventListener('iron-select', function() { 
        pages.selected = tabs.selected;
    });


  var wall = new Freewall("#freewall");
  wall.reset({
    selector: '.brick',
    animate: true,
    cellW: 320,
    cellH: 'auto',
    onResize: function() {
      wall.fitWidth();
    }
  });

  wall.container.find('.brick img').load(function() {
    wall.fitWidth();
  });
</script>
@stop
