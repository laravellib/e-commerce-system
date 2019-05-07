<?php

use App\Models\Category;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */

$factory->define(Category::class, function (Faker $faker) {
    $name = $faker->unique()->word;

    return [
        'name' => ucfirst($name),
        'slug' => str_slug($name)
    ];
});
