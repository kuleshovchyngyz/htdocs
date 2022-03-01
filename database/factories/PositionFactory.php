<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Position;
use App\Region;
use App\Query;
use Faker\Generator as Faker;

$factory->define(Position::class, function (Faker $faker, $params) {
	$date = $faker->dateTimeBetween('-1 month');
    $position = $faker->numberBetween(1,100);
    $odds = $faker->numberBetween(0,1);
    return $odds === 0 
    	? [
	        'google_position' 	=> $position,
	        'google_date' 		=> $date,
	        'project_id' 		=> $params['project_id'],
	        'region_id' 		=> $faker->numberBetween(1,7),
	        'query_id' 			=> $params['query_id']
	    ]
	    : [
	        'yandex_position' 	=> $position,
	        'yandex_date' 		=> $date,
	        'project_id' 		=> $params['project_id'],
	        'region_id' 		=> $faker->numberBetween(1,7),
	        'query_id' 			=> $params['query_id']
	    ];
});
