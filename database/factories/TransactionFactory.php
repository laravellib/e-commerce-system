<?php

use App\Models\Order;
use App\Models\Transaction;
use Faker\Generator as Faker;

use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */
$factory->define(Transaction::class, function (Faker $faker) {
    return [
        'order_id' => function () {
            return factory(Order::class)->create()->id;
        },
        'total' => 2000,
    ];
});
