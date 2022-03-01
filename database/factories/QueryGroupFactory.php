<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\QueryGroup;
use Faker\Generator as Faker;
use App\Region;

$factory->define(QueryGroup::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'region_id' => Region::all()->random()->id,
        'is_active' => 1
    ];
});
