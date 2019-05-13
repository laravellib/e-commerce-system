<?php

namespace App\Http\Controllers\Orders;

use App\Cart\Cart;
use App\Events\Order\OrderCreated;
use App\Http\Requests\Orders\OrderStoreRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'cart.sync', 'cart.notEmpty']);
    }

    public function store(OrderStoreRequest $request, Cart $cart)
    {
        $order = $this->createOrder($request, $cart);

        $order->products()->sync($cart->products()->forSyncing());

//        $order->load(['products', 'address', 'shippingMethod]);

        event(new OrderCreated($order));

        return new OrderResource($order);
    }

    protected function createOrder(OrderStoreRequest $request, Cart $cart)
    {
        return $request->user()->orders()->create(
            array_merge($request->only(['address_id', 'shipping_method_id']), [
                'subtotal' => $cart->subtotal()->amount()
            ])
        );
    }
}