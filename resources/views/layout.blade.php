<!DOCTYPE html>
<html lang="en-US">
	<head>
		 <style>
		   #map {
			height: 400px;
			width: 100%;
		   }
		</style>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
		<title>What Do You Want To Eat?</title>
	</head>
	<body>
		<div class="jumbotron">
			<h1>What THE FUCK Do You Want To Eat?</h1>
			<br>
			<a href="/LaravelWhatDoYouWantToEat/public" class='btn btn-info' role='button'>Home</a>
			<br>
		</div>
		
        <div class='container-fluid'>
         	@yield('content')
        </div>
        
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    </body>
</html>