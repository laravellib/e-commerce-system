<?php

namespace Tests\Unit\Cart;

use App\Cart\Cart;
use App\Models\ProductVariation;
use App\Models\ShippingMethod;
use App\Models\User;
use App\Money\Money;
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

    /** @test */
    function it_can_delete_a_product_from_the_cart()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
        );
        $user->cart()->attach(
            $product = factory(ProductVariation::class)->create(), [
                'quantity' => 1
            ]
        );

        $cart->delete($product->id);

        $this->assertEmpty($user->fresh()->cart);
    }

    /** @test */
    function it_can_delete_all_products_from_the_cart()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $user->cart()->attach(
            factory(ProductVariation::class)->create(), [
                'quantity' => 1
            ]
        );

        $user->cart()->attach(
            factory(ProductVariation::class)->create(), [
                'quantity' => 3
            ]
        );

        $cart->empty();

        $this->assertEmpty($user->fresh()->cart);
    }

    /** @test */
    function it_knows_if_is_empty_of_quantities()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $user->cart()->attach(
            factory(ProductVariation::class)->create(), [
                'quantity' => 0
            ]
        );

        $this->assertTrue($cart->isEmpty());
    }

    /** @test */
    function it_is_not_empty_with_items()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $user->cart()->attach(
            factory(ProductVariation::class)->create(), [
                'quantity' => 1
            ]
        );

        $this->assertFalse($cart->isEmpty());
    }

    /** @test */
    function it_returns_a_money_instance_for_the_subtotal()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $this->assertInstanceOf(Money::class, $cart->subtotal());
    }

    /** @test */
    function it_gets_the_correct_subtotal()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $user->cart()->attach(
            factory(ProductVariation::class)->create([
                'price' => 1000
            ]), [
                'quantity' => 2
            ]
        );

        $this->assertEquals(2000, $cart->subtotal()->amount());
    }

    /** @test */
    function it_returns_a_money_instance_for_the_total()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $this->assertInstanceOf(Money::class, $cart->total());
    }

    /** @test */
    function it_can_return_the_correct_total_without_shipping()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $user->cart()->attach(
            factory(ProductVariation::class)->create([
                'price' => 1000
            ]), [
                'quantity' => 2
            ]
        );

        $this->assertEquals(2000, $cart->total()->amount());
    }

    /** @test */
    function it_can_return_the_correct_total_with_shipping()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $shipping = factory(ShippingMethod::class)->create([
            'price' => 1000,
        ]);

        $user->cart()->attach(
            factory(ProductVariation::class)->create([
                'price' => 1000
            ]), [
                'quantity' => 2
            ]
        );

        $this->assertEquals(3000, $cart->withShipping($shipping)->total()->amount());
    }

    /** @test */
    function it_syncs_the_cart_to_update_quantities()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $user->cart()->attach(
            factory(ProductVariation::class)->create(), [
                'quantity' => 2
            ]
        );

        $cart->sync();

        $this->assertEquals(0, $user->fresh()->cart->first()->pivot->quantity);
    }

    /** @test */
    function it_syncs_the_cart_to_update_quantities_with_multiple_products()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $product = factory(ProductVariation::class)->create();
        $anotherProduct = factory(ProductVariation::class)->create();

        $user->cart()->attach([
            $product->id => [
                'quantity' => 2,
            ],
            $anotherProduct->id => [
                'quantity' => 0,
            ],
        ]);

        $cart->sync();

        $this->assertTrue($cart->hasChanged());
    }

    /** @test */
    function it_can_check_if_the_cart_has_changed_after_syncing()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $user->cart()->attach(
            factory(ProductVariation::class)->create(), [
                'quantity' => 2
            ]
        );

        $cart->sync();

        $this->assertTrue($cart->hasChanged());
    }

    /** @test */
    function it_doesnt_change_the_cart_by_default()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $cart->sync();

        $this->assertFalse($cart->hasChanged());
    }

    /** @test */
    function it_returns_products_in_cart()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $user->cart()->attach(
            $product = factory(ProductVariation::class)->create(), [
                'quantity' => 2
            ]
        );

        $this->assertInstanceOf(ProductVariation::class, $cart->products()->first());
        $this->assertTrue($cart->products()->first()->is($product));
    }
}
