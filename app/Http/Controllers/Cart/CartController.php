<?php

namespace App\Http\Controllers\Cart;

use App\Cart\Cart;
use App\Http\Requests\Cart\CartStoreRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\CartUpdateRequest;
use App\Http\Resources\Cart\CartResource;
use App\Models\ProductVariation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request, Cart $cart)
    {
        $request->user()->load(['cart.product.variations.stock', 'cart.stock']);

        return (new CartResource($request->user()))
            ->additional([
                'meta' => $this->meta($cart)
            ]);
    }

    public function store(CartStoreRequest $request, Cart $cart)
    {
        $cart->add($request->get('products'));

        return response()->json([], Response::HTTP_CREATED);
    }

    public function update(ProductVariation $productVariation, CartUpdateRequest $request, Cart $cart)
    {
        $cart->update($productVariation->id, $request->get('quantity'));

        return response()->json([], Response::HTTP_CREATED);
    }

    public function destroy(ProductVariation $productVariation, Cart $cart)
    {
        $cart->delete($productVariation->id);
    }

    private function meta(Cart $cart): array
    {
        return [
            'empty' => $cart->isEmpty()
        ];
    }
}
