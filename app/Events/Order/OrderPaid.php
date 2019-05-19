<?php

namespace App\Events\Order;

use App\Models\Order;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class OrderPaid
{
    use Dispatchable, SerializesModels;

    /**
     * @var Order
     */
    public $order;

    /**
     * Create a new event instance.
     *
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }
}
