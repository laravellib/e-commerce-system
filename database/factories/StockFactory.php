<?php

use App\Models\Stock;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */

$factory->define(Stock::class, function (Faker $faker) {
    return [
        'quantity' => 1
    ];
});
