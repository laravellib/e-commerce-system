<?php

namespace Tests\Unit\Listeners\Order;

use App\Events\Order\OrderPaid;
use App\Models\Order;
use Tests\TestCase;

class MarkOrderProcessingTest extends TestCase
{
    /** @test */
    function it_marks_order_status_as_processing()
    {
        $order = factory(Order::class)->create();

        event(new OrderPaid($order));

        $this->assertEquals(Order::PROCESSING, $order->fresh()->status);
    }
}
