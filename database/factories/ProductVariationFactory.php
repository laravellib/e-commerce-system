<?php

use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\ProductVariationType;
use Faker\Generator as Faker;

$factory->define(ProductVariation::class, function (Faker $faker) {
    return [
        'name' => ucfirst($faker->word),
        'product_id' => function () {
            return factory(Product::class)->create()->id;
        },
        'type_id' => function () {
            return factory(ProductVariationType::class)->create()->id;
        },
    ];
});
