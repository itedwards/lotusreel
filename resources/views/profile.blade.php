@extends('master')

@section('title')
	<?php
		if(isset($id))
		{
			$user = DB::table('users')
				->where('id', '=', $id)
				->get();
		}
		else
		{
			$user = Auth::user();
		}
	?>
	{{ $user['firstname']." ".$user['lastname'] }}
@stop

@section('content')
	<h1>Hi</h1>
@stop
