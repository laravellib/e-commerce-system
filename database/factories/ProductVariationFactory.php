<?php

use App\Models\Product;
use App\Models\ProductVariation;
use Faker\Generator as Faker;

$factory->define(ProductVariation::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->name,
        'product_id' => function () {
            return factory(Product::class)->create()->id;
        }
    ];
});
