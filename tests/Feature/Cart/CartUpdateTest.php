<?php

namespace Tests\Feature\Cart;

use App\Models\ProductVariation;
use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

class CartUpdateTest extends TestCase
{
    /** @test */
    function it_fails_if_unauthenticated()
    {
        $this->putJson(route('cart.update', factory(ProductVariation::class)->create()))
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    function it_fails_if_the_product_cannot_be_found()
    {
        $this->signIn()->putJson(route('cart.update', 1))
            ->assertNotFound();
    }

    /** @test */
    function it_requires_a_quantity_for_updating_cart()
    {
        $variation = factory(ProductVariation::class)->create();

        $this->signIn()->putJson(route('cart.update', $variation))
            ->assertJsonValidationErrors('quantity');
    }

    /** @test */
    function it_requires_a_quantity_to_be_a_numeric_for_updating_cart()
    {
        $variation = factory(ProductVariation::class)->create();

        $this->signIn()->putJson(route('cart.update', $variation), [
            'quantity' => 'not-numeric'
        ])
            ->assertJsonValidationErrors('quantity');
    }

    /** @test */
    function it_requires_a_quantity_to_be_at_least_1_for_updating_cart()
    {
        $variation = factory(ProductVariation::class)->create();

        $this->signIn()->putJson(route('cart.update', $variation), [
            'quantity' => 0
        ])
            ->assertJsonValidationErrors('quantity');
    }

    /** @test */
    function a_user_cart_product_quantity_can_be_updated()
    {
        $user = factory(User::class)->create();
        $variation = factory(ProductVariation::class)->create();
        $user->cart()->attach($variation, ['quantity' => 1]);

        $this->signIn($user)->putJson(route('cart.update', $variation), [
            'quantity' => 3
        ]);

        $this->assertDatabaseHas('cart_user', [
            'product_variation_id' => $variation->id,
            'user_id' => $user->id,
            'quantity' => 3,
        ]);
    }
}
