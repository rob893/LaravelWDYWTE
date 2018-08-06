<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class ResultsController extends Controller
{
    public function getResults(){
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $coords = explode(",", $_POST['currentCoords']);
        
        $keyword = $_POST['keyword'];
		$keyword = str_replace(' ','',$keyword);
        $meters = (int)$_POST['distance'] * 1610;
        
        if($meters == 0){
            $meters = 15000;
        }
        
        if(!isset($_POST['data'])){
            $apiLink = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=".$coords[0].",".$coords[1]."&radius=".$meters."&type=restaurant&keyword=".$keyword."&key=AIzaSyDL2PSZaOLv96XKHrWuENmPT8kwnn8vLRM";
			
            try{
				$data = json_decode(file_get_contents($apiLink), true);
			} 
			catch(\Exception $e) {
				return view('results', [
					'resultsBool' => false,
					'content' => 'Sorry, no results found!'
				]);
			}
			
            $_POST['data'] = $data;
        } else {
            $data = unserialize(base64_decode($_POST['data']));
            $selection = $_POST['selection'];
            array_splice($data['results'], $selection, 1);

            if(count($data['results']) == 0 && !array_key_exists('next_page_token', $data)){
                unset($_POST['data']);
                return view('results', [
                    'resultsBool' => false,
                    'content' => 'You have looked through all the results! Why don\'t you be less picky next time?'
                ]);
            } else if(count($data['results']) == 0 && array_key_exists('next_page_token', $data)){
                $apiLink = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?key=AIzaSyDL2PSZaOLv96XKHrWuENmPT8kwnn8vLRM&pagetoken=".$data['next_page_token'];
                $data = json_decode(file_get_contents($apiLink), true);
                $_POST['data'] = $data;
            }
        }
        
        if($data['status'] == 'ZERO_RESULTS'){
            
            unset($_POST['data']);
            return view('results', [
                'resultsBool' => false,
                'content' => 'Sorry, no results found!'
            ]);
            
        } else {
            $selection = rand(0, count($data['results']) -1);
            
            if(array_key_exists('opening_hours', $data['results'][$selection])){
                if($data['results'][$selection]['opening_hours']['open_now'] == 1){
                    $open = "Yes";
                } else if($data['results'][$selection]['opening_hours']['open_now'] == 0){
                    $open = "No";
                }
            } else {
                $open = "No posted hours";
            }
            
            $name = $data['results'][$selection]['name'];
            $address = $data['results'][$selection]['vicinity'];
            $openNow = "Open Now: ".$open;
            
            return view('results', [
                'resultsBool' => true,
                'data' => base64_encode(serialize($data)),
                'selection' => $selection,
                'name' => $name,
                'address' => $address,
                'openNow' => $openNow,
                'lat' => $data['results'][$selection]['geometry']['location']['lat'],
                'lng' => $data['results'][$selection]['geometry']['location']['lng'],
                'keyword' => $_POST['keyword'],
                'currentCoords' => $_POST['currentCoords'],
                'distance' => $_POST['distance']
            ]);
        }
    }
    
    public function printResultsArray(){
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $coords = explode(",", $_POST['currentCoords']);
        
        $keyword = $_POST['keyword'];
        $meters = (int)$_POST['distance'] * 1610;
        
        if($meters == 0){
            $meters = 15000;
        }
        
        $apiLink = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=".$coords[0].",".$coords[1]."&radius=".$meters."&type=restaurant&keyword=".$keyword."&key=AIzaSyDL2PSZaOLv96XKHrWuENmPT8kwnn8vLRM";
        $data = json_decode(file_get_contents($apiLink), true);
        
        echo "<p>Number of results: ".count($data['results'])."</p>";
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }
}