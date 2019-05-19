<?php

namespace App\Listeners\Order;

use App\Events\Order\OrderPaymentFailed;

class MarkOrderFailed
{
    /**
     * Handle the event.
     *
     * @param  OrderPaymentFailed  $event
     * @return void
     */
    public function handle(OrderPaymentFailed $event)
    {
        $event->order->update([
            'status' => $event->order::PAYMENT_FAILED
        ]);
    }
}
