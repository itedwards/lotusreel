<?php
	$user = DB::table('users')->where('id', '=', $comment['user_id'])->get();
	$user = json_decode(json_encode($user[0]), true);
?>
<li class="media">
	<a class="media-left" href="#">
		<img class="media-object img-circle" src="<? echo $user['profile_photo']; ?>">
	</a>
	<div class="media-body">
	    <strong><? echo $user['firstname']; echo " ".$user['lastname'].": "; ?></strong><br>
	    <? echo $comment['comment']; ?>
	</div>
</li>