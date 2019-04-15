<?php

namespace Tests\Unit\Money;

use App\Money\Money;
use Tests\TestCase;

class MoneyTest extends TestCase
{
    /** @test */
    function it_can_get_the_raw_amount()
    {
        $money = new Money(1000);

        $this->assertEquals(1000, $money->amount());
    }

    /** @test */
    function it_can_get_the_formatted_amount()
    {
        $money = new Money(1000);

        $this->assertEquals('$10.00', $money->formatted());
    }

    /** @test */
    function it_can_add_up()
    {
        $money = new Money(1000);

        $money->add(new Money(2000));

        $this->assertEquals(3000, $money->amount());
    }
}
