@extends('master')

@section('title')
	Sign Up | LotusReel
@stop

@section('content')
	<h1>Sign Up</h1>
	@foreach($errors->all() as $message) 
		<div class='error'>{{ $message }}</div>
	@endforeach
	<form class="form-signin" action="/sign-up" method="post" enctype="multipart/form-data">
		<div class="form-group">
			<input class="form-control" name="firstname" placeholder="First Name" type="text">
		</div>
		<div class="form-group">
			<input class="form-control" name="lastname" placeholder="Last Name" type="text">
		</div>
		<div class="form-group">
			<input class="form-control" name="email" placeholder="E-Mail" type="email" id="inputEmail">
		</div>		
		<div class="form-group">
			<input class="form-control" name="password" placeholder="Password" type="password">
	    </div>
	    <div class="form-group">
			<input class="form-control" name="bio" placeholder="Your bio (100 characters)" type="text">
		</div>
	    <div class="form-group">
	    	<label for="InputFile">Profile Photo</label>
	    	<input type="file" id="InputFile" name="profile_photo">
		</div>
		<div class="form-group">
	    	<label for="InputFile">Cover Photo</label>
	    	<input type="file" id="InputFile" name="cover_photo">
		</div>
	    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
		<div class="form-group">
			<button type="submit" class="btn btn-default">Sign Up</button>
		</div>
	</form>
@stop