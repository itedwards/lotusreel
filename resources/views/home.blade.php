@extends('master')

@section('head')
  <link rel="import" href="/bower_components/paper-toolbar/paper-toolbar.html">
  <link rel="import" href="/bower_components/paper-icon-button/paper-icon-button.html">
  <link rel="import" href="/bower_components/iron-icons/iron-icons.html">
  <link rel="import" href="/bower_components/iron-icons/maps-icons.html">
  <link rel="import" href="/bower_components/paper-input/paper-input.html">

@stop

@section('content')
<?php
	$user = Auth::user();
	
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

  $followed_array = unserialize($user['followed']);
  for($i = 0; $i < sizeof($followed_array); $i++){
    $current_user_posts = DB::table('posts')->where('user_id', '=', $followed_array[$i])->get();
    $current_user_posts = json_decode(json_encode($current_user_posts), true);
    for($j = 0; $j < sizeof($current_user_posts); $j++){
      array_push($new_array, $current_user_posts[$j]);
    }
  }
  
?>

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
        <li class="active"><a href="/"><span class="glyphicon glyphicon-list" aria-hidden="true"></span> My Reel <span class="sr-only">(current)</span></a></li>
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
        <li><a href="/upload"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Upload</a></li>
        <li><a href="/logout">Log Out</a></li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
<br>
<br>

<div style="margin:20px">
  <div class="col-md-8">

		<ul class="list-group media-list media-list-stream">
			<?php	
				$new_array = array_reverse($new_array);
				foreach($new_array as $post){
					$user_query = DB::table('users')->where('id', '=', $post['user_id'])->get();
					$post_user = json_decode(json_encode($user_query[0]), true);

          $likesArray = unserialize($post['likes']);

          $collection = DB::table('collections')
            ->where('id', $post['collection_id'])
            ->get();
          $collection = json_decode(json_encode($collection[0]), true);

          if($post['likes'] == ''){
            $totLikes = 0;
          }
          else{
            $totLikes = sizeof($likesArray);
          }

          $liked = false;
          for($i = 0; $i < $totLikes; $i++){
            if($likesArray[$i] == $user['id']){
              $liked = true;
            }
          }

          if($post['comments'] == ''){
            $totComments = 0;
          }
          else{
            $totComments = sizeof(unserialize($post['comments']));
          }

          $allComments = DB::table('comments')->where('post_id', '=', $post['id'])->get();
          $allComments = json_decode(json_encode($allComments), true);
            
  					
  				if($post['file_type'] == 'jpg' || $post['file_type'] == 'jpeg' || $post['file_type'] == 'bmp' || $post['file_type'] == 'png' || $post['file_type'] == 'gif'){
			?>
					<li class="media list-group-item p-a">
						<a class="media-left" href="#">
							<img class="media-object img-circle" src="<? echo $post_user['profile_photo']; ?>">
						</a>
            <? if(Auth::id() == $post['user_id']){ ?>
              <a class="media-right" href="/delete-post/<?php echo $post['id']?>">

              </a>
            <? } ?>
						<div class="media-body">
							<div class="media-heading">
								<small class="pull-right text-muted">4 min</small>

								<h4><?php echo $post['title']; ?></h4>
                <small class="text-muted" style="display:inline-block">by</small>
                <h5 style="display:inline-block"><?php echo $post_user['firstname']; echo " ".$post_user['lastname']; ?></h5>
                <small style="display:inline-block" class="text-muted">in</small>
                <h5 style="display:inline-block"><?php echo $collection['collection_name']; ?></h5>
							</div>
	
							<p>
				  		  <?php echo $post['description']; ?>	            
				  		</p>
			  			<div class="media-body-inline-grid" data-grid="images">
					  	  <div style="display: none">
						  		<img data-action="zoom" data-width="1050" data-height="700" src="<? echo $post['file'] ?>">
						  	</div>
					    </div>

						  <hr>

              <div class="pull-right text-muted">
                <span class="icon icon-thumbs-up"></span><span id="like-count-<? echo $post['id']; ?>"><? echo " ".$totLikes ?></span>
                <span class="icon icon-chat"></span><? echo " ".$totComments ?>
              </div>

              <?
                if($liked == false){
              ?>
						      <button class="btn btn-default-outline like-buttons" type="button" id="<? echo $post['id']; ?>"><span class="icon icon-thumbs-up"></span> Like</button>
              <?
                }
                else{
              ?>
                  <button class="btn btn-primary unlike-buttons" type="button" id="<? echo $post['id']; ?>"><span class="icon icon-thumbs-up"></span> Liked!</button>
              <?
                }
              ?>
              <button class="btn btn-default-outline" type="button" id="comment-button"><span class="icon icon-chat"></span> Comment</button>

              <hr>

              <div id="comments">
                <ul class="media-list m-b">
                  <?
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

                  <li class="media" id="commentArea">
                    <a class="media-left" href="#">
                      <img class="media-object img-circle" src="<? echo $user['profile_photo']; ?>">
                    </a>
                    <div class="media-body">
                      <strong><? echo $user['firstname']; echo " ".$user['lastname'].": "; ?></strong>
                      <input type="text" class="form-control commentField" placeholder="Comment" name="comment" id="<? echo 'comment-'.$post['id']; ?>">
                    </div>
                  </li>
                </ul>
              </div>

				    </div>
					</li>
			<?
					}
					else if($post['file_type'] == 'mov' || $post['file_type'] == 'mp4' || $post['file_type'] == 'wmv'){
			?>
						<li class="media list-group-item p-a">
							<a class="media-left" href="#">
								<img class="media-object img-circle" src="<? echo $post_user['profile_photo']; ?>">
							</a>
							<div class="media-body">
								<div class="media-heading">
									<small class="pull-right text-muted">4 min</small>
									<h5><?php echo $post_user['firstname']; echo " ".$post_user['lastname']; ?></h5>
								</div>
								<p>
					  			<?php echo $post['description']; ?>	            
					  		</p>
					  			
				  			<video controls="" name="media" width="1050" height="700">
				  				<source src="<?php echo $post['file']; ?>" type="video/mp4">
				  			</video>

					  		<hr>

					  		<button class="btn btn-default-outline like-buttons" type="button" id="<? echo $post['id']; ?>"><span class="icon icon-thumbs-up"></span> Like</button>
                <button class="btn btn-default-outline" type="button" id="comment-button"><span class="icon icon-chat"></span> Comment</button>

                <hr>

                <div id="comments">
                  <ul class="media-list m-b">
                    <li class="media">
                      <a class="media-left" href="#">
                       <img class="media-object img-circle" src="../assets/img/avatar-fat.jpg">
                      </a>
                      <div class="media-body">
                        <strong>Jacon Thornton:</strong>
                        Donec id elit non mi porta gravida at eget metus. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Donec ullamcorper nulla non metus auctor fringilla. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Sed posuere consectetur est at lobortis.
                      </div>
                    </li>
                    <li class="media">
                      <a class="media-left" href="#">
                        <img class="media-object img-circle" src="../assets/img/avatar-mdo.png">
                      </a>
                      <div class="media-body">
                        <strong>Mark Otto:</strong>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.
                      </div>
                    </li>
                    <li class="media">
                      <a class="media-left" href="#">
                        <img class="media-object img-circle" src="../assets/img/avatar-mdo.png">
                      </a>
                      <div class="media-body">
                        <strong><? echo $user['firstname']; echo " ".$user['lastname'].": "; ?></strong>
                        <input type="text" class="form-control commentField" placeholder="Comment" name="comment" id="<? echo $post['id']; ?>">
                      </div>
                    </li>
                  </ul>
                </div>

					  	</div>

						</li>
			<?php
					}
					else if($post['file_type'] == 'mp3' || $post['file_type'] == 'm4a'){			
			?>
						<li class="media list-group-item p-a">
							<a class="media-left" href="#">
								<img class="media-object img-circle" src="<? echo $post_user['profile_photo']; ?>">
							</a>
							<div class="media-body">
								<div class="media-heading">
									<small class="pull-right text-muted">4 min</small>
									<h5><?php echo $post_user['firstname']; echo " ".$post_user['lastname']; ?></h5>
								</div>
								<p>
					  				<?php echo $post['description']; ?>	            
					  			</p>
	
				  				<audio controls="" name="media">
					  				<source src="<?php echo $post['file']; ?>" type="audio/mp3">
					  			</audio>

					  		<hr>

					  		<button class="btn btn-default-outline like-buttons" type="button" id="<? echo $post['id']; ?>"><span class="icon icon-thumbs-up"></span> Like</button>
                <button class="btn btn-default-outline" type="button" id="comment-button"><span class="icon icon-chat"></span> Comment</button>

                <hr>

                <div id="comments">
                  <ul class="media-list m-b">
                    <li class="media">
                      <a class="media-left" href="#">
                       <img class="media-object img-circle" src="../assets/img/avatar-fat.jpg">
                      </a>
                      <div class="media-body">
                        <strong>Jacon Thornton:</strong>
                        Donec id elit non mi porta gravida at eget metus. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Donec ullamcorper nulla non metus auctor fringilla. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Sed posuere consectetur est at lobortis.
                      </div>
                    </li>
                    <li class="media">
                      <a class="media-left" href="#">
                        <img class="media-object img-circle" src="../assets/img/avatar-mdo.png">
                      </a>
                      <div class="media-body">
                        <strong>Mark Otto:</strong>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.
                      </div>
                    </li>
                    <li class="media">
                      <a class="media-left" href="#">
                        <img class="media-object img-circle" src="../assets/img/avatar-mdo.png">
                      </a>
                      <div class="media-body">
                        <strong><? echo $user['firstname']; echo " ".$user['lastname'].": "; ?></strong>
                        <input type="text" class="form-control" placeholder="Comment" name="comment" id="commentField">
                      </div>
                    </li>
                  </ul>
                </div>

							</div>

						</li>
			<?php			
					}
				}
			?>
		</ul>		
	</div>
    <div class="col-md-4">
      <div class="panel panel-default panel-profile m-b-md">
        <div class="panel-heading" style="background-image: url(<?php echo $user['cover_photo']; ?>);"></div>
        <div class="panel-body text-center">
          <a href="/profile/<?php echo $user['url_id']; ?>">
            <img
              class="panel-profile-img"
              src= "<?php echo $user['profile_photo']; ?>"/>
          </a>

          <h5 class="panel-title">
            <a class="text-inherit" href="/profile/<?php echo $user['url_id']; ?>"><?php echo $user['firstname']; echo " ".$user['lastname']; ?></a>
          </h5>

          <p class="m-b-md"><?php echo $user['bio'];?></p>
          <ul class="panel-menu">
            <li class="panel-menu-item">
              <a href="#userModal" class="text-inherit" data-toggle="modal">
                Posts
                <h5 class="m-y-0"><? echo $totPosts; ?></h5>
              </a>
            </li>
          </ul>
        </div>
      </div>

      <div class="panel panel-default m-b-md hidden-xs">
        <div class="panel-body">
        <h5 class="m-t-0">Likes <small>· <a href="#">View All</a></small></h5>
        <ul class="media-list media-list-stream">
          <li class="media m-b">
            <a class="media-left" href="#">
              <img
                class="media-object img-circle"
                src="assets/img/avatar-fat.jpg">
            </a>
            <div class="media-body">
              <strong>Jacob Thornton</strong> @fat
              <div class="media-body-actions">
                <button class="btn btn-primary-outline btn-sm">
                  <span class="icon icon-add-user"></span> Follow</button>
              </div>
            </div>
          </li>
           <li class="media">
            <a class="media-left" href="#">
              <img
                class="media-object img-circle"
                src="assets/img/avatar-mdo.png">
            </a>
            <div class="media-body">
              <strong>Mark Otto</strong> @mdo
              <div class="media-body-actions">
                <button class="btn btn-primary-outline btn-sm">
                  <span class="icon icon-add-user"></span> Follow</button></button>
              </div>
            </div>
          </li>
        </ul>
        </div>
        <div class="panel-footer">
          Dave really likes these nerds, no one knows why though.
        </div>
      </div>

      <div class="panel panel-default panel-link-list">
        <div class="panel-body">
          © 2015 LotusReel

          <a href="#">About</a>
          <a href="#">Help</a>
          <a href="#">Terms</a>
          <a href="#">Privacy</a>
          <a href="#">Cookies</a>
          <a href="#">Ads </a>

          <a href="#">info</a>
          <a href="#">Brand</a>
          <a href="#">Blog</a>
          <a href="#">Status</a>
          <a href="#">Apps</a>
          <a href="#">Jobs</a>
          <a href="#">Advertise</a>
        </div>
      </div>
    </div>
  </div>
</div>
@stop

@section('footer')
  <script src="assets/js/chart.js"></script>
  <script src="assets/js/toolkit.js"></script>
  <script src="assets/js/application.js"></script>
  <script src="assets/js/post_interaction.js"></script>
  <script>
    // execute/clear BS loaders for docs
    $(function(){
      if (window.BS&&window.BS.loader&&window.BS.loader.length) {
        while(BS.loader.length){(BS.loader.pop())()}
      }
    })

    $( "#comment-button" ).click(function() {
      $( "#commentField" ).focus();
    });
    
  </script>


@stop