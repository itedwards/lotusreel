<html>
	<head>

		<title>@yield('title', 'LotusReel')</title>

		<meta charset='utf-8'>
    	<link rel='stylesheet' href='{{ asset('css/foobar.css') }}'>

    	<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

		<!-- Optional theme -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">

		<!-- Latest compiled and minified JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
		
		<link href='https://fonts.googleapis.com/css?family=Playball' rel='stylesheet' type='text/css'>
		
    	@yield('head')

	<head>
	<body>
		@yield('nav')
			 
		@yield('content')

	</body>
</html>