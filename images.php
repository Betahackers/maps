<?php
require_once('conf.php');
$json = str_replace('var articles = [', '', file_get_contents('data4_travel_times.json'));
$json = substr($json, 0, -1);
$data = json_decode($json);
foreach ($data->article as $article) {
	$locations = $article->locations;
	foreach ($locations as $location) { 
		// if ($location->image) {
		// 	continue;
		// }
		print $location->name . PHP_EOL;
		$image = getImage($location);
		sleep(60);
		$location->image = $image;
	}
}
file_put_contents('data5_images.json', 'var articles = [' . json_encode($data) . ']');

function getImage($location){
	$key = GOOGLE_API_KEY;
	$url = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=" . $location->latitude . ',' . $location->longitude . "&radius=500&name=" . urlencode($location->name) . "&sensor=false&key=$key";
	// print $url;
	$response = json_decode(file_get_contents($url));
	$reference = $response->results[0]->photos[0]->photo_reference;
	$url = 'https://maps.googleapis.com/maps/api/place/photo?photoreference=' . $reference . '&maxwidth=400&sensor=false&key=' . $key;
	print $url . PHP_EOL . PHP_EOL;
	return $url;
}

