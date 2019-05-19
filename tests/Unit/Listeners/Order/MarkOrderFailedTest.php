<?php

namespace Tests\Unit\Listeners\Order;

use App\Cart\Cart;
use App\Events\Order\OrderPaymentFailed;
use App\Listeners\Order\EmptyCart;
use App\Models\Order;
use App\Models\ProductVariation;
use App\Models\User;
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
