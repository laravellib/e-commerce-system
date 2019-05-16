<?php

namespace App\Cart\Payments\Gateways;

use App\Cart\Payments\GatewayCustomer;
use App\Cart\Payments\PaymentGateway;
use App\Models\User;
use Stripe\Customer as StripeCustomer;

class StripePaymentGateway implements PaymentGateway
{
    private $user;

    public function withUser(User $user): PaymentGateway
    {
        $this->user = $user;

        return $this;
    }

    public function user(): User
    {
        return $this->user;
    }

    public function createCustomer(): GatewayCustomer
    {
        if ($this->user->customer_id) {
            return $this->getCustomer($this->user->customer_id);
        }

        $customer = new StripeGatewayCustomer($this, $this->createStripeCustomer());

        $this->user->update([
            'customer_id' => $customer->id(),
        ]);

        return $customer;
    }

    private function createStripeCustomer()
    {
        return StripeCustomer::create([
            'email' => $this->user->email
        ]);
    }

    public function getCustomer($id)
    {
        return new StripeGatewayCustomer($this, StripeCustomer::retrieve($id));
    }
}
