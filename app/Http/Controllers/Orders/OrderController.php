<?php

namespace App\Http\Controllers\Orders;

use App\Cart\Cart;
use App\Events\Order\OrderCreated;
use App\Http\Requests\Orders\OrderStoreRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api']);
        $this->middleware(['cart.sync', 'cart.notEmpty'])->only('store');
    }

    public function index(Request $request)
    {
        $orders = $request->user()->orders()
            ->with([
                'products.product.variations.stock',
                'products.type',
                'products.stock',
                'address',
                'shippingMethod'
            ])
            ->latest()
            ->paginate(10);

        return OrderResource::collection($orders);
    }

    public function store(OrderStoreRequest $request, Cart $cart)
    {
        $order = $this->createOrder($request, $cart);

        $order->products()->sync($cart->products()->forSyncing());

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
