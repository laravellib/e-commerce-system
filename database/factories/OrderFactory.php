<?php

use App\Models\Address;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\ShippingMethod;
use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */

$factory->define(Order::class, function (Faker $faker) {
    return [
        'address_id' => function () {
            return \factory(Address::class)->create()->id;
        },
        'user_id' => function () {
            return \factory(User::class)->create()->id;
        },
        'shipping_method_id' => function () {
            return \factory(ShippingMethod::class)->create()->id;
        },
        'payment_method_id' => function () {
            return \factory(PaymentMethod::class)->create()->id;
        },
        'subtotal' => 1000,
    ];
});
