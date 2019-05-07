<?php

use App\Models\ShippingMethod;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */

$factory->define(ShippingMethod::class, function (Faker $faker) {
    return [
        'name' => 'Royal Mail',
        'price' => 1000,
    ];
});
