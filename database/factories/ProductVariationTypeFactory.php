<?php

use App\Models\ProductVariationType;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */

$factory->define(ProductVariationType::class, function (Faker $faker) {
    return [
        'name' => ucfirst($faker->unique()->word),
    ];
});
