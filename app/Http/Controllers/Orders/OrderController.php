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
        $order = $this->createOrder($request, $cart);

        $products = $cart->products()->keyBy('id')->map(function ($product) {
            return [
                'quantity' => $product->pivot->quantity,
            ];
        })->toArray();

        $order->products()->sync($products);

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
