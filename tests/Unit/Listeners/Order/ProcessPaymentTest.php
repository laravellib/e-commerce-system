<?php

namespace Tests\Unit\Listeners\Order;

use App\Cart\Payments\GatewayCustomer;
use App\Cart\Payments\PaymentGateway;
use App\Events\Order\OrderCreated;
use App\Events\Order\OrderPaid;
use App\Events\Order\OrderPaymentFailed;
use App\Exceptions\PaymentFailedException;
use App\Listeners\Order\ProcessPayment;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Mockery;
use Tests\TestCase;

class ProcessPaymentTest extends TestCase
{
    /** @test */
    function it_charges_the_chosen_payment_the_correct_amount()
    {
        Event::fake();

        [$user, $payment, $order, $event] = $this->createEvent();

        [$gateway, $customer] = $this->mockFlow();

        $customer->shouldReceive('charge')->with(
            $order->paymentMethod, $order->total()->amount()
        );

        $listener = new ProcessPayment($gateway);

        $listener->handle($event);
    }

    /** @test */
    function it_fires_the_order_paid_event()
    {
        Event::fake();

        [$user, $payment, $order, $event] = $this->createEvent();

        [$gateway, $customer] = $this->mockFlow();

        $customer->shouldReceive('charge')->with(
            $order->paymentMethod, $order->total()->amount()
        );

        $listener = new ProcessPayment($gateway);

        $listener->handle($event);

        Event::assertDispatched(OrderPaid::class, function (OrderPaid $event) use ($order) {
             return $event->order->is($order);
        });
    }

    /** @test */
    function it_fires_the_order_failed()
    {
        Event::fake();

        [$user, $payment, $order, $event] = $this->createEvent();

        [$gateway, $customer] = $this->mockFlow();

        $customer->shouldReceive('charge')
            ->with(
                $order->paymentMethod, $order->total()->amount()
            )
            ->andThrow(PaymentFailedException::class);

        $listener = new ProcessPayment($gateway);

        $listener->handle($event);

        Event::assertDispatched(OrderPaymentFailed::class, function (OrderPaymentFailed $event) use ($order) {
            return $event->order->is($order);
        });
    }

    protected function createEvent()
    {
        $user = factory(User::class)->create();

        $payment = factory(PaymentMethod::class)->create([
            'user_id' => $user->id,
        ]);

        $order = factory(Order::class)->create([
            'user_id' => $user->id,
            'payment_method_id' => $payment->id,
        ]);

        $event = new OrderCreated($order);

        return [$user, $payment, $order, $event];
    }

    protected function mockFlow()
    {
        $gateway = Mockery::mock(PaymentGateway::class);

        $customer = Mockery::mock(GatewayCustomer::class);

        $gateway->shouldReceive('withUser')->andReturn($gateway);

        $gateway->shouldReceive('getCustomer')->andReturn($customer);

        return [$gateway, $customer];
    }
}
