<?php

namespace Tests\Unit\Listeners\Order;

use App\Events\Order\OrderPaid;
use App\Events\Order\OrderPaymentFailed;
use App\Models\Order;
use Tests\TestCase;

class CreateTransactionTest extends TestCase
{
    /** @test */
    function it_creates_a_transaction()
    {
        $order = factory(Order::class)->create();

        event(new OrderPaid($order));

        $this->assertCount(1, $order->transactions);
        $this->assertEquals($order->total()->amount(), $order->transactions->first()->total);
    }
}
