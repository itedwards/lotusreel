<html>
	<head>

		<title>@yield('title', 'LotusReel')</title>

		<meta charset='utf-8'>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="keywords" content="">
		<meta name="author" content="">
		<meta name="csrf-token" content="{{ csrf_token() }}" />
		
		<script type="text/javascript">
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
		</script>
		
		
		
    	<link rel='stylesheet' href='{{ asset('css/foobar.css') }}'>

    	<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

		<!-- Optional theme -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">

		<!-- Latest compiled and minified JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
		
		<link href='https://fonts.googleapis.com/css?family=Playball' rel='stylesheet' type='text/css'>
		
		<link href="/assets/css/toolkit.css" rel="stylesheet">
    
		<link href="/assets/css/application.css" rel="stylesheet">

		<style>
			/* note: this is a hack for ios iframe for bootstrap themes shopify page */
			/* this chunk of css is not part of the toolkit :) */
			body {
				width: 1px;
				min-width: 100%;
				*width: 100%;
			}	
		</style>
		
    	@yield('head')

	<head>
	<body class="with-top-navbar">

		@yield('nav')
			 
		@yield('content')

	</body>
</html>