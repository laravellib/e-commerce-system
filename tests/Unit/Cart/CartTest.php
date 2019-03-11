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

    /** @test */
    function it_increments_quantity_when_adding_more_products()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $product = factory(ProductVariation::class)->create();

        $cart->add([
            ['id' => $product->id, 'quantity' => 2]
        ]);

        $cart->add([
            ['id' => $product->id, 'quantity' => 3]
        ]);

        tap($user->fresh()->cart, function ($cart) {
            $this->assertCount(1, $cart);
            $this->assertEquals(5, $cart->first()->pivot->quantity);
        });
    }

    /** @test */
    function it_can_update_quantities_in_the_cart()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $user->cart()->attach(
            $product = factory(ProductVariation::class)->create(), [
                'quantity' => 1
            ]
        );

        $cart->update($product->id, 2);

        $this->assertEquals(2, $user->fresh()->cart->first()->pivot->quantity);
    }
}
