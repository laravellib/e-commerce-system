<?php

namespace App\Cart\Payments\Gateways;

use App\Cart\Payments\GatewayCustomer;
use App\Cart\Payments\PaymentGateway;
use App\Models\PaymentMethod;
use Stripe\Customer as StripeCustomer;

class StripeGatewayCustomer implements GatewayCustomer
{
    private $gateway;

    private $customer;

    public function __construct(PaymentGateway $gateway, StripeCustomer $customer)
    {
        $this->gateway = $gateway;
        $this->customer = $customer;
    }

    public function charge(PaymentMethod $card, $amount)
    {
        // TODO: Implement charge() method.
    }

    public function addCart($token)
    {
        $card = $this->customer->sources->create([
            'source' => $token,
        ]);

        $this->customer->default_source = $card->id;
        $this->customer->save();

        return $this->gateway->user()->paymentMethods()->create([
            'provider_id' => $card->id,
            'card_type' => $card->brand,
            'last_four' => $card->last4,
            'default' => true,
        ]);
    }

    public function id()
    {
        return $this->customer->id;
    }
}
