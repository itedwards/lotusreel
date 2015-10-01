@extends('master')

@section('title')
	Login | LotusReel
@stop

@section('content')
	<h1>Login</h1>
	
	{{ Form::open(array('url' => '/')) }}

		Email:
		{{ Form::text('email') }}<br><br>

		Password:
		{{ Form::password('password') }}<br><br>

		{{ Form::submit('Submit') }}

	{{ Form::close() }}
@stop	