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
		
		<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />

		{{ Form::submit('Submit') }}
		
	{{ Form::close() }}
@stop	