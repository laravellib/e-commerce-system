<?php

namespace App\Http\Controllers\PaymentMethods;

use App\Cart\Payments\PaymentGateway;
use App\Http\Resources\PaymentMethodResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentMethodController extends Controller
{
    private $gateway;

    public function __construct(PaymentGateway $gateway)
    {
        $this->middleware(['auth:api']);
        $this->gateway = $gateway;
    }

    public function index(Request $request)
    {
        return PaymentMethodResource::collection(
            $request->user()->paymentMethods
        );
    }

    public function store(Request $request)
    {
        $card = $this->gateway->withUser($request->user())
            ->createCustomer()
            ->addCart($request->token);

        // $card PaymentMethod
    }
}
