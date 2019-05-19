<?php

namespace Tests\Unit\Listeners\Order;

use App\Cart\Cart;
use App\Events\Order\OrderPaid;
use App\Events\Order\OrderPaymentFailed;
use App\Listeners\Order\EmptyCart;
use App\Models\Order;
use App\Models\ProductVariation;
use App\Models\User;
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
