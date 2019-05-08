<?php

namespace App\Http\Controllers\Orders;

use App\Cart\Cart;
use App\Http\Requests\Orders\OrderStoreRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api']);
    }
    
    public function store(OrderStoreRequest $request, Cart $cart)
    {
        if ($cart->isEmpty()) {
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }

        $order = $this->createOrder($request, $cart);

        $order->products()->sync($cart->products()->forSyncing());

        return response()->json($order, Response::HTTP_OK);
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
