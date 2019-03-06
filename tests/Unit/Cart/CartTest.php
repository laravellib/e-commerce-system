<?php

namespace Tests\Unit\Cart;

use App\Cart\Cart;
use App\Models\ProductVariation;
use App\Models\User;
use Tests\TestCase;

class CartTest extends TestCase
{
    /** @test */
    function it_can_add_products_to_the_cart()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $product = factory(ProductVariation::class)->create();

        $cart->add([
            ['id' => $product->id, 'quantity' => 2]
        ]);

        tap($user->fresh()->cart, function ($cart) {
            $this->assertCount(1, $cart);
            $this->assertEquals(2, $cart->first()->pivot->quantity);
        });
    }
}
