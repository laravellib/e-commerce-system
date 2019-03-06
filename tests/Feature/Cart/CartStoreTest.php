<?php

namespace Tests\Feature\Cart;

use App\Models\ProductVariation;
use Illuminate\Http\Response;
use Tests\TestCase;

class CartStoreTest extends TestCase
{
    /** @test */
    function it_fails_if_unauthenticated()
    {
        $this->postJson(route('cart.store'))
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    function it_requires_products()
    {
        $this->signIn()->postJson(route('cart.store'))
            ->assertJsonValidationErrors(['products']);
    }

    /** @test */
    function it_requires_products_to_be_an_array()
    {
        $this->signIn()->postJson(route('cart.store'), [
            'products' => 'not-an-array'
        ])
            ->assertJsonValidationErrors(['products']);
    }

    /** @test */
    function it_requires_products_array_with_ids()
    {
        $this->signIn()->postJson(route('cart.store'), [
            'products' => [
                ['quantity' => 1],
            ]
        ])
            ->assertJsonValidationErrors(['products.0.id']);
    }

    /** @test */
    function it_requires_products_to_exist()
    {
        $this->signIn()->postJson(route('cart.store'), [
            'products' => [
                ['id' => 1, 'quantity' => 1],
            ]
        ])
            ->assertJsonValidationErrors(['products.0.id']);
    }

    /** @test */
    function it_requires_products_quantity_to_be_a_numeric()
    {
        $this->signIn()->postJson(route('cart.store'), [
            'products' => [
                ['id' => 1, 'quantity' => 'a'],
            ]
        ])
            ->assertJsonValidationErrors(['products.0.quantity']);
    }

    /** @test */
    function it_requires_products_quantity_to_be_at_least_1()
    {
        $this->signIn()->postJson(route('cart.store'), [
            'products' => [
                ['id' => 1, 'quantity' => 0],
            ]
        ])
            ->assertJsonValidationErrors(['products.0.quantity']);
    }

    /** @test */
    function user_can_add_products_to_the_user_cart()
    {
        $product = factory(ProductVariation::class)->create();

        $this->signIn()->postJson(route('cart.store'), [
            'products' => [
                ['id' => $product->id, 'quantity' => 2],
            ]
        ]);

        $this->assertDatabaseHas('cart_user', [
            'product_variation_id' => $product->id,
            'quantity' => 2,
        ]);
    }
}
