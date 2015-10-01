@extends('master')

@section('content')
	<form action="/new-post-form" method="post">
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
@stop