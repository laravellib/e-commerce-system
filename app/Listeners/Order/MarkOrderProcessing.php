<?php

namespace App\Listeners\Order;

use App\Events\Order\OrderPaymentFailed;

class MarkOrderProcessing
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
            'status' => $event->order::PROCESSING
        ]);
    }
}
