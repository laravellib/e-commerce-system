<?php

namespace App\Cart\Payments;

use App\Models\User;

interface PaymentGateway
{
    public function withUser(User $user): PaymentGateway;

    public function createCustomer(): GatewayCustomer;

    public function getCustomer(): GatewayCustomer;

    public function user(): User;
}
