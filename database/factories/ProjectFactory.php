<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Project;
use App\User;
use App\Region;
use App\QueryGroup;
use Faker\Generator as Faker;

$factory->define(Project::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'url' => $faker->url,
        'client_id' => User::all()->random()->id,
        'manager_id' => User::all()->random()->id,
        'is_active' => 1
    ];
});
