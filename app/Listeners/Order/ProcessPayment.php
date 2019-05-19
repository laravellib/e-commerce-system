<?php

namespace App\Listeners\Order;

use App\Cart\Payments\PaymentGateway;
use App\Events\Order\OrderCreated;
use App\Events\Order\OrderPaid;
use App\Events\Order\OrderPaymentFailed;
use App\Exceptions\PaymentFailedException;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessPayment implements ShouldQueue
{
    /**
     * @var PaymentGateway
     */
    private $gateway;

    /**
     * Create the event listener.
     *
     * @param PaymentGateway $gateway
     */
    public function __construct(PaymentGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * Handle the event.
     *
     * @param  OrderCreated  $event
     * @return void
     */
    public function handle(OrderCreated $event)
    {
        try {
            $this->gateway->withUser($event->order->user)
                ->getCustomer()
                ->charge(
                    $event->order->paymentMethod,
                    $event->order->total()->amount()
                );

            event(new OrderPaid($event->order));
        } catch (PaymentFailedException $e) {
            event(new OrderPaymentFailed($event->order));
        }
    }
}
