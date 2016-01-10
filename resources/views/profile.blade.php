@extends('master')

@section('title')
	
@stop

<?php
	$query = DB::table('users')
		->where('id', '=', $id)
		->get();
	$user = json_decode(json_encode($query[0]), true);
				
?>

@section('nav')
<nav class="navbar navbar-inverse navbar-fixed-top app-navbar">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse-main">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.html">
        <img src="assets/img/brand-white.png" alt="brand">
      </a>
    </div>
    <div class="navbar-collapse collapse" id="navbar-collapse-main">

        <ul class="nav navbar-nav hidden-xs">
          <li class="active">
            <a href="index.html">Home</a>
          </li>
          <li>
            <a href="profile/<?php echo Auth::id(); ?>">Profile</a>
          </li>
          <li>
            <a data-toggle="modal" href="#msgModal">Messages</a>
          </li>
          <li>
            <a href="docs/index.html">Docs</a>
          </li>
        </ul>

        <ul class="nav navbar-nav navbar-right m-r-0 hidden-xs">
          <li >
            <a class="app-notifications" href="notifications/index.html">
              <span class="icon icon-bell"></span>
            </a>
          </li>
          <li>
            <button class="btn btn-default navbar-btn navbar-btn-avitar" data-toggle="popover">
              <img class="img-circle" src="assets/img/avatar-dhg.png">
            </button>
          </li>
        </ul>

        <form class="navbar-form navbar-right app-search" role="search">
          <div class="form-group">
            <input type="text" class="form-control" data-action="grow" placeholder="Search">
          </div>
        </form>

        <ul class="nav navbar-nav hidden-sm hidden-md hidden-lg">
          <li><a href="index.html">Home</a></li>
          <li><a href="profile/<?php echo Auth::id(); ?>">Profile</a></li>
          <li><a href="notifications/index.html">Notifications</a></li>
          <li><a data-toggle="modal" href="#msgModal">Messages</a></li>
          <li><a href="docs/index.html">Docs</a></li>
          <li><a href="#" data-action="growl">Growl</a></li>
          <li><a href="login/index.html">Logout</a></li>
        </ul>

        <ul class="nav navbar-nav hidden">
          <li><a href="#" data-action="growl">Growl</a></li>
          <li><a href="login/index.html">Logout</a></li>
        </ul>
      </div>
  </div>
</nav>
@stop

@section('content')
	<div class="profile-header text-center"
     style="background-image: url(<?php echo $user['cover_photo'];?>);">
  <div class="container">
    <div class="container-inner">
      <img class="img-circle media-object" src="<?php echo $user['profile_photo'];?>">
      <h3 class="profile-header-user"><?php echo $user['firstname']; echo " ".$user['lastname']; ?></h3>
      <p class="profile-header-bio">
        <?php echo $user['bio']; ?>
      </p>
    </div>
  </div>

  <nav class="profile-header-nav">
    <ul class="nav nav-tabs">
      <li class="active">
        <a href="#">Photos</a>
      </li>
      <li>
        <a href="#">Others</a>
      </li>
      <li>
        <a href="#">Anothers</a>
      </li>
    </ul>
  </nav>
</div>

<?php if($user['id'] != Auth::id()){ ?>
  <form action="/add-follower/<?php echo $user['id']; ?>" method="get">
    <div class="media-body-actions">
        <button class="btn btn-primary-outline btn-sm" action="submit">
        <span class="icon icon-add-user"></span>Follow</button>
    </div>
  </form>
<? } ?>

<div class="container m-y-md" data-grid="images">
  <div>
    <img data-width="640" data-height="400" data-action="zoom" src="../assets/img/instagram_5.jpg">
  </div>

  <div>
    <img data-width="640" data-height="640" data-action="zoom" src="../assets/img/instagram_6.jpg">
  </div>

  <div>
    <img data-width="640" data-height="640" data-action="zoom" src="../assets/img/instagram_7.jpg">
  </div>

  <div>
    <img data-width="640" data-height="640" data-action="zoom" src="../assets/img/instagram_8.jpg">
  </div>

  <div>
    <img data-width="640" data-height="640" data-action="zoom" src="../assets/img/instagram_9.jpg">
  </div>

  <div>
    <img data-width="640" data-height="640" data-action="zoom" src="../assets/img/instagram_10.jpg">
  </div>

  <div>
    <img data-width="640" data-height="400" data-action="zoom" src="../assets/img/instagram_11.jpg">
  </div>

  <div>
    <img data-width="640" data-height="640" data-action="zoom" src="../assets/img/instagram_12.jpg">
  </div>

  <div>
    <img data-width="640" data-height="400" data-action="zoom" src="../assets/img/instagram_13.jpg">
  </div>

  <div>
    <img data-width="640" data-height="640" data-action="zoom" src="../assets/img/instagram_14.jpg">
  </div>

  <div>
    <img data-width="640" data-height="640" data-action="zoom" src="../assets/img/instagram_15.jpg">
  </div>

  <div>
    <img data-width="640" data-height="640" data-action="zoom" src="../assets/img/instagram_16.jpg">
  </div>

  <div>
    <img data-width="640" data-height="640" data-action="zoom" src="../assets/img/instagram_17.jpg">
  </div>

  <div>
    <img data-width="640" data-height="640" data-action="zoom" src="../assets/img/instagram_18.jpg">
  </div>

  <div>
    <img data-width="640" data-height="400" data-action="zoom" src="../assets/img/instagram_1.jpg">
  </div>

  <div>
    <img data-width="640" data-height="640" data-action="zoom" src="../assets/img/instagram_2.jpg">
  </div>
</div>

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
    </script>
@stop
