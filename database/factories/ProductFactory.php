<?php

use App\Models\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    $name = $faker->unique()->word;

    return [
        'name' => ucfirst($name),
        'description' => $faker->sentence(10),
        'slug' => str_slug($name),
        'price' => $faker->numberBetween(100, 5000)
    ];
});
