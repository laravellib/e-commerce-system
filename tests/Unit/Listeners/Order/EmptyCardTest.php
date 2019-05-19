<?php

namespace Tests\Unit\Listeners\Order;

use App\Cart\Cart;
use App\Listeners\Order\EmptyCart;
use App\Models\ProductVariation;
use App\Models\User;
use Tests\TestCase;

class EmptyCardTest extends TestCase
{
    /** @test */
    function it_should_clear_the_cart()
    {
        $user = factory(User::class)->create();

        $product = factory(ProductVariation::class)->create();

        $user->cart()->attach($product);

        $listener = new EmptyCart(new Cart($user));
        $listener->handle();

        $this->assertEmpty($user->cart);
    }
}
