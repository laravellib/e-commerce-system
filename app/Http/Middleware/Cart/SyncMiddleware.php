<?php

namespace App\Http\Middleware\Cart;

use App\Cart\Cart;
use Closure;
use Illuminate\Http\{JsonResponse, Request, Response};

class SyncMiddleware
{
    /**
     * @var Cart
     */
    private $cart;

    /**
     * SyncMiddleware constructor.
     *
     * @param Cart $cart
     */
    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->cart->sync();

        if ($this->cart->hasChanged()) {
            return $this->sendChangedResponse();
        }

        return $next($request);
    }

    /**
     * @return JsonResponse
     */
    private function sendChangedResponse(): JsonResponse
    {
        return response()->json([
            'message' => 'Oh no, some items in your cart have changed. Please review these changes before placing your order'
        ], Response::HTTP_CONFLICT);
    }
}
