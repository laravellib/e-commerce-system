<?php

namespace App\Listeners\Order;

use App\Events\Order\OrderPaid;

class MarkOrderProcessing
{
    /**
     * Handle the event.
     *
     * @param  OrderPaid  $event
     * @return void
     */
    public function handle(OrderPaid $event)
    {
        $event->order->update([
            'status' => $event->order::PROCESSING
        ]);
    }
}
