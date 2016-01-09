@extends('master')

@section('head')
<script type="text/javascript">
$(document).ready(function(){ 
    $("#myTab a").click(function(e){
    	e.preventDefault();
    	$(this).tab('show');
    });
});
</script>
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
?>
<div class="growl" id="app-growl"></div>

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
          <li><a href="/logout">Logout</a></li>
        </ul>

        <ul class="nav navbar-nav hidden">
          <li><a href="#" data-action="growl">Growl</a></li>
          <li><a href="/logout">Logout</a></li>
        </ul>
      </div>
  </div>
</nav>


<div class="container p-t-md">
	

    <div class="col-md-8">
    	<div class="panel-group" id="accordion">
	        <div class="panel panel-default">
	        	<a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
	           		<div class="panel-heading">
	                	<h4 class="panel-title">
	                    	<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> New Post
	                    	<span class="glyphicon glyphicon-chevron-down pull-right" aria-hidden="true"></span>
						</h4>
					</div>
	        	</a>
            	<div id="collapseTwo" class="panel-collapse collapse">
                	<div class="panel-body">
						<form action="/new-post-form" method="post" enctype="multipart/form-data">
							<div class="form-group">
								<label for="title">Title</label>
								<input type="text" class="form-control" id="title" placeholder="Give Post a Title" name="title">
							</div>
							<div class="form-group">
								<label for="description">Description</label>
								<textarea class="form-control" rows="3" placeholder="Give Post a Description" name="description"></textarea>
							</div>
							<div class="form-group">
								<label for="InputFile">Your Creation</label>
								<input type="file" id="InputFile" name="file">
								<p class="help-block">Make it anything.</p>
							</div>
							<button type="submit" class="btn btn-default">Submit</button>
						</form>
					</div>
				</div>
			</div>
    	</div>

		<ul class="list-group media-list media-list-stream">
			<?php	
				$new_array = array_reverse($new_array);
				foreach($new_array as $post){
					$user_query = DB::table('users')->where('id', '=', $post['user_id'])->get();
					$post_user = json_decode(json_encode($user_query[0]), true);
  					
  					if($post['file_type'] == 'jpg' || $post['file_type'] == 'bmp' || $post['file_type'] == 'png' || $post['file_type'] == 'gif'){
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
				  			<div class="media-body-inline-grid" data-grid="images">
					  			<div style="display: none">
						  		<img data-action="zoom" data-width="1050" data-height="700" src="<? echo $post['file'] ?>">
						  	</div>
						</div>
						<hr>
						<button class="btn btn-default-outline" type="button"><span class="icon icon-thumbs-up"></span>Like</button>
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
					  			
					  			<video controls="" name="media">
					  				<source src="<?php echo $post['file']; ?>" type="video/mp4">
					  			</video>
					  		<hr>
					  		<button class="btn btn-default-outline" type="button"><span class="icon icon-thumbs-up"></span>Like</button>
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
					  		<button class="btn btn-default-outline" type="button"><span class="icon icon-thumbs-up"></span>Like</button>
							</div>
						</li>
			<?php			
					}
				}
			?>
		</ul>		
	</div>
    <div class="col-md-4">
      <div class="alert alert-warning alert-dismissible hidden-xs" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <a class="alert-link" href="profile/index.html">Visit your profile!</a> Check your self, you aren't looking too good.
      </div>

      <div class="panel panel-default panel-profile m-b-md">
        <div class="panel-heading" style="background-image: url(<?php echo $user['cover_photo'] ?>);"></div>
        <div class="panel-body text-center">
          <a href="profile/index.html">
            <img
              class="panel-profile-img"
              src= "<?php echo $user['profile_photo']?>"/>
          </a>

          <h5 class="panel-title">
            <a class="text-inherit" href="profile/index.html"><?php echo $user['firstname']; echo " ".$user['lastname']; ?></a>
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
          © 2015 Bootstrap

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


    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/chart.js"></script>
    <script src="assets/js/toolkit.js"></script>
    <script src="assets/js/application.js"></script>
    <script>
      // execute/clear BS loaders for docs
      $(function(){
        if (window.BS&&window.BS.loader&&window.BS.loader.length) {
          while(BS.loader.length){(BS.loader.pop())()}
        }
      })
    </script>
	
@stop