<?php

namespace Tests\Unit\Listeners\Order;

use App\Events\Order\OrderPaymentFailed;
use App\Models\Order;
use Tests\TestCase;

class MarkOrderFailedTest extends TestCase
{
    /** @test */
    function it_marks_order_status_as_payment_failed()
    {
        $order = factory(Order::class)->create();

        event(new OrderPaymentFailed($order));

        $this->assertEquals(Order::PAYMENT_FAILED, $order->fresh()->status);
    }
}
