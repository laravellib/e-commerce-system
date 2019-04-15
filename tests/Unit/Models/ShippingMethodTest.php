<?php

namespace Tests\Unit\Models;

use App\Models\Country;
use App\Models\ShippingMethod;
use App\Money\Money;
use Tests\TestCase;

class ShippingMethodTest extends TestCase
{
    /** @test */
    function it_returns_a_money_instance_for_the_price()
    {
        $shipping = factory(ShippingMethod::class)->create();

        $this->assertInstanceOf(Money::class, $shipping->money);
    }

    /** @test */
    function it_returns_a_formatted_price_instance_for_the_price()
    {
        $shipping = factory(ShippingMethod::class)->create([
            'price' => 0,
        ]);

        $this->assertEquals('$0.00', $shipping->priceFormatted);
    }

    /** @test */
    function it_belongs_to_many_countries()
    {
        $shipping = factory(ShippingMethod::class)->create();

        $shipping->countries()->attach(
            factory(Country::class)->create()
        );

        $this->assertInstanceOf(Country::class, $shipping->countries->first());
    }
}
