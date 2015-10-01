@extends('master')

@section('title')
	Sign Up | LotusReel
@stop

@section('content')
	<h1>Sign Up</h1>
	@foreach($errors->all() as $message) 
		<div class='error'>{{ $message }}</div>
	@endforeach
	<form class="form-signin" action="/sign-up" method="post">
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
	    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
		<div class="form-group">
			<button type="submit" class="btn btn-default">Sign Up</button>
		</div>
	</form>
@stop