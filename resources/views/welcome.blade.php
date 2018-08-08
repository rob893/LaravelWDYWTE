@extends('layout')

@section('content')

<div class='container-fluid'>
	<div style="display: none;" id="map"></div>
</div>

<script>
	var currentCoords = [];
	var map;
	
	function initMap() {
		map = new google.maps.Map(document.getElementById('map'), {
			zoom: 18,
			center: {lat: 33.252756903371775, lng: -85.81798553466797 } //Default location is Ashland AL
		});
		currentCoords[0] = 33.252756903371775;
		currentCoords[1] = -85.81798553466797;
		getLocation();  //Centers map on user's location. Requires the server to have an SSL certificate.
		document.getElementById("currentCoords").value = currentCoords;
	}

	
	function getLocation() {
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(showPosition);
			
		} else {
			alert("Geolocation is not supported by this browser.");
		}
	}
	
	function showPosition(position) {
		var lat = position.coords.latitude;
		var lng = position.coords.longitude;
		currentCoords[0] = lat;
		currentCoords[1] = lng;
		document.getElementById("currentCoords").value = currentCoords;
		map.setCenter(new google.maps.LatLng(lat, lng));
	}
	
</script>

<script async defer
	src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDL2PSZaOLv96XKHrWuENmPT8kwnn8vLRM&callback=initMap">
</script>

<br>

<form action='/OtherStuff/LaravelWDYWTE/public/results' method='post' enctype='multipart/form-data'>
	@csrf
	<div class='row'>
		<div class='col-sm-4'>
			<div class='form-group'>
        		<input type='hidden' name='currentCoords' id='currentCoords'>
        		<label for='keyword'>Keyword (Not Required):</label><br>
        		<input type='text' name='keyword' id='keyword'><br><br>
        		<label for='distance'>Miles willing to travel (Not Required):</label><br>
        		<input type='number' name='distance' id='distance'><br><br>
        		<input name='submit' type='submit' value="I Don't Know">
        	</div>
        </div>
	</div>
</form>

@endsection