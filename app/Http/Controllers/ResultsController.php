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
        $meters = (int)$_POST['distance'] * 1610;
        
        if($meters == 0){
            $meters = 15000;
        }
        
        if(!isset($_POST['data'])){
            $apiLink = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=".$coords[0].",".$coords[1]."&radius=".$meters."&type=restaurant&keyword=".$keyword."&key=AIzaSyDL2PSZaOLv96XKHrWuENmPT8kwnn8vLRM";
            $data = json_decode(file_get_contents($apiLink), true);
            $_POST['data'] = $data;
        } else {
            $data = unserialize(base64_decode($_POST['data']));
            $selection = $_POST['selection'];
            array_splice($data['results'], $selection, 1);

            if(count($data['results']) == 0){
                
                unset($_POST['data']);
                return view('results', [
                    'resultsBool' => false,
                    'content' => 'You have looked through all the results! Why don\'t you be less picky next time?'
                ]);
            }
        }
        
        if(count($data['results']) == 0){
            
            unset($_POST['data']);
            return view('results', [
                'resultsBool' => false,
                'content' => 'Sorry, no results found!'
            ]);
            
        } else {
            $selection = rand(0, count($data['results']) -1);
            
            if($data['results'][$selection]['opening_hours']['open_now'] == 1){
                $open = "Yes";
            } else if($data['results'][$selection]['opening_hours']['open_now'] == 0){
                $open = "No";
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