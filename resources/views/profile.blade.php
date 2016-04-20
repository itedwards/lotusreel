@extends('master')

@section('title')
	
@stop

@section('head')
  <link rel="import" href="/bower_components/paper-toolbar/paper-toolbar.html">
  <link rel="import" href="/bower_components/paper-icon-button/paper-icon-button.html">
  <link rel="import" href="/bower_components/iron-icons/iron-icons.html">
  <link rel="import" href="/bower_components/iron-image/iron-image.html">
  <link rel="import" href="/bower_components/paper-tabs/paper-tabs.html">
  <link rel="import" href="/bower_components/iron-menu-behavior/iron-menu-behavior.html">
  <link rel="import" href="/bower_components/iron-fit-behavior/iron-fit-behavior.html">
@stop

<?php
  $query = DB::table('users')
    ->where('url_id', '=', $url_id)
    ->get();
  $user = json_decode(json_encode($query[0]), true);
				
?>

@section('content')
<paper-toolbar>
  <span class="title" style="font-family: 'Playball', cursive;">LotusReel</span>
  <form class="navbar-form navbar-left" role="search">
      <input type="text" class="form-control" placeholder="Search">
    <paper-icon-button icon="search"></paper-icon-button>
  </form>
  <paper-icon-button icon="account-circle"></paper-icon-button>
  <paper-icon-button icon="settings"></paper-icon-button>
</paper-toolbar>


<iron-image id="coverImage" style="width:100%; height:540px;" sizing="cover" src="<? echo $user['cover_photo']; ?>"></iron-image>


<paper-tabs selected="0">
  <paper-tab>My Portfolio</paper-tab>
  <paper-tab>My Information</paper-tab>
  <paper-tab>Shared</paper-tab>
</paper-tabs>

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
