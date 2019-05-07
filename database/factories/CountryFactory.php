<?php

use App\Models\Country;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */

$factory->define(Country::class, function (Faker $faker) {
    return [
        'code' => $faker->countryCode,
        'name' => $faker->country,
    ];
});
