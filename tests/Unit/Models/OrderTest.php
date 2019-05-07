<?php

namespace Tests\Unit\Models;

use App\Models\Address;
use App\Models\Country;
use App\Models\Order;
use App\Models\ShippingMethod;
use App\Models\User;
use Tests\TestCase;

class OrderTest extends TestCase
{
    /** @test */
    function it_belongs_to_a_user()
    {
        $order = factory(Order::class)->create();

        $this->assertInstanceOf(User::class, $order->user);
    }

    /** @test */
    function it_belongs_to_an_address()
    {
        $order = factory(Order::class)->create();

        $this->assertInstanceOf(Address::class, $order->address);
    }

    /** @test */
    function it_belongs_to_a_shipping_method()
    {
        $order = factory(Order::class)->create();

        $this->assertInstanceOf(ShippingMethod::class, $order->shippingMethod);
    }
}
