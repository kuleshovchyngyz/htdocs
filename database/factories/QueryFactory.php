<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Query;
use App\QueryGroup;
use Faker\Generator as Faker;

$factory->define(Query::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence($nbWords = 4, $variableNbWords = true),
        'is_active' => 1,
        'query_group_id' => QueryGroup::all()->random()->id,
    ];
});
