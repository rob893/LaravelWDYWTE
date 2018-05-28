@extends('layout')

@section('content')

@if (!$resultsBool)
    <p>{{ $content }}</p>
    <form action='/LaravelWhatDoYouWantToEat/public' method='get' enctype='multipart/form-data'>
    	<br>
        <input name='submit' type='submit' value='Try another search!' >
    </form>

@else
    <p>How about this place?</p>
    <p>
        {{ $name }}
        <br>
        {{ $address }}
        <br>
        {{ $openNow }}
    </p>
    
    <div id="map"></div>
    
    <script>
    	function initMap() {
    		var myLatLng = {lat: {{ $lat }}, lng: {{ $lng }} };
        	
    		map = new google.maps.Map(document.getElementById('map'), {
    			zoom: 18,
    			center: myLatLng
    		});
    
    		var marker = new google.maps.Marker({
    	          position: myLatLng,
    	          map: map,
    	          title: 'EAT HERE DAMNIT!'
    	    });
    	}
    </script>
    
    <script async defer
    	src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDL2PSZaOLv96XKHrWuENmPT8kwnn8vLRM&callback=initMap">
    </script>
    
    <form action='/LaravelWhatDoYouWantToEat/public/results' method='post' enctype='multipart/form-data'>
    	@csrf
    	<input type='hidden' name='currentCoords' id='currentCoords' value='{{ $currentCoords }}' >
    	<input type='hidden' name='keyword' id='keyword' value='{{ $keyword }}' >
    	<input type='hidden' name='distance' id='distance' value='{{ $distance }}' >
    	<input type='hidden' name='data' id='data' value='{{ $data }}' >
    	<input type='hidden' name='selection' id='selection' value='{{ $selection }}' >
    	<br>
        <input name='submit' type='submit' value="No, that place sucks ass!">
    </form>
@endif
@endsection