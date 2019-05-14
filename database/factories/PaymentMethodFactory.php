<?php

use App\Models\PaymentMethod;
use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */

$factory->define(PaymentMethod::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'card_type' => 'Visa',
        'last_four' => '4242',
        'provider_id' => $faker->unique()->word,
        'default' => true,
    ];
});
