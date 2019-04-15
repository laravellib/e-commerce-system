<?php

namespace Tests\Unit\Models;

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
}
