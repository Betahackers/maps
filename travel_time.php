<?php
$data = json_decode(file_get_contents('data3_php.json'));
foreach ($data->article as $article) {
	$locations = $article->locations;
	for ($i=0; $i < count($locations) - 1; $i++) { 
		$current = $locations[$i];
		$next = $locations[$i + 1];
		$metro = getTime($current, $next, 'transit');
		$walking = getTime($current, $next, 'walking');
		print 'time between ' . $current->name . ' and ' . $next->name . ': ' . PHP_EOL;
		print "\tMetro: " . $metro/60 . PHP_EOL;
		print "\tWalking: " . $walking/60 . PHP_EOL;
		if ($walking/60 - $metro/60 > 15) {
			$travel_time = $metro;
			$travel_mode = 'transit';
		} else {
			$travel_time = $walking;
			$travel_mode = 'walking';
		}
		print "\tTravel mode: $travel_mode\n";
		print PHP_EOL;
		$article->locations[$i]->travel_time = $travel_time;
		$article->locations[$i]->travel_mode = $travel_mode;
	}
}
file_put_contents('data4_travel_times.json', 'var articles = [' . json_encode($data) . ']');

function getTime($s1, $s2, $mode){
	sleep(2);
	$url = "http://maps.googleapis.com/maps/api/directions/json?origin=" . $s1->latitude . "," . $s1->longitude . "&destination=" . $s2->latitude . "," . $s2->longitude . "&sensor=false&mode=$mode&departure_time=" . time();
	// print $url; exit;
	$json = file_get_contents($url);
	$directions = json_decode($json);
	$time = 0;
	$legs = $directions->routes[0]->legs;
	foreach ($legs as $leg) {
		$time += $leg->duration->value;
	}
	return $time;
}

