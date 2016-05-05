@extends('master')

@section('title')
	
@stop

<?php     
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
  <script src="assets/js/freewall.js"></script>


  <style is="custom-style">
    .intro-header {
      padding-top: 0px; /* If you're making other pages, make sure there is 50px of padding to make sure the navbar doesn't overlap content! */
      padding-bottom: 0px;
      text-align: center;
      color: #f8f8f8;
      background: url(<? echo $cover_photo; ?>) no-repeat center center fixed;
      background-size: cover;
    }
    .cafe-header { 
      @apply(--paper-font-headline); 
    }
    .cafe-light { 
      color: var(--paper-grey-600); 
    }
    .cafe-location {
      float: right;
      font-size: 15px;
      vertical-align: middle;
    }
    .cafe-reserve { color: var(--google-blue-500); }
      iron-icon.star {
        --iron-icon-width: 16px;
        --iron-icon-height: 16px;
        color: var(--paper-amber-500);
    }
    iron-icon.star:last-of-type { 
      color: var(--paper-grey-500); 
    }

    .profile-cells {
      width: 320px;
      height: 320px;
    }

    .freewall{
      margin: 15px;
    }
  </style>


@stop

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

<paper-tabs selected="0">
  <paper-tab>My Portfolio</paper-tab>
  <paper-tab>My Information</paper-tab>
  <paper-tab>Shared</paper-tab>
</paper-tabs>

<iron-pages selected="0">
  
<div class="tab-content">
  <div class="freewall" id="freewall">
      <?php 
        $new_array = array_reverse($new_array);
        foreach($new_array as $post){
      ?>
        <div class="profile-cells">
          <paper-card image="<? echo $post['file']; ?>">
            <div class="card-content">
              <div class="cafe-header"><? echo $post['title']; ?>
                <div class="cafe-location cafe-light">
                  <iron-icon icon="communication:location-on"></iron-icon>
                  <span>4 min</span>
                </div>
              </div>
              <div class="cafe-rating">
          
              </div>
              <p>$・</p>
              <p class="cafe-light"><? echo $post['description']; ?></p>
            </div>
            <div class="card-actions">
              <div class="horizontal justified">
                <button class="btn btn-default-outline like-buttons" type="button" id="<? echo $post['id']; ?>"><span class="icon icon-thumbs-up"></span> Like</button>
                <button class="btn btn-default-outline" type="button" id="comment-button"><span class="icon icon-chat"></span> Comment</button>
              </div>
            </div>
          </paper-card>
        </div>
      <?
        }
      ?>
    </div>

  <div class="tab-content">
    
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
    selector: '.profile-cells',
    animate: true,
    cellW: 20,
    cellH: 200,
    onResize: function() {
      wall.fitWidth();
    }
  });

  wall.fitWidth();
  // for scroll bar appear;
  $(window).trigger("resize");

</script>
@stop
