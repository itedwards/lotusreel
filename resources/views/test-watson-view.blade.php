@extends('master')


@section('content')
<?php
	$results = json_decode(json_encode($resp['concepts']), true);
	
	$keywords = array();

	if(sizeof($results) < 3){
		for($i = 0; $i < sizeof($results); $i++){
			array_push($keywords, $results[$i]['text']);
		}
	}
	else{
		for($i = 0; $i < 3; $i++){
			array_push($keywords, $results[$i]['text']);
		}
	}

	print_r($keywords);
?>
@stop