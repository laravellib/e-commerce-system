<?php

namespace Tests\Feature\Cart;

use App\Models\ProductVariation;
use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

class CartIndexTest extends TestCase
{
    /** @test */
    function it_fails_if_unauthenticated()
    {
        $this->getJson(route('cart.index'))
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    function it_shows_products_in_the_user_cart()
    {
        $user = factory(User::class)->create();

        $user->cart()->attach(
            $product = factory(ProductVariation::class)->create()
        );

        $response = $this->signIn($user)->getJson(route('cart.index'));

        $response->assertJsonFragment([
            'id' => $product->id
        ]);
    }

    /** @test */
    function it_shows_if_the_cart_is_empty()
    {
        $user = factory(User::class)->create();

        $response = $this->signIn($user)->getJson(route('cart.index'));

        $response->assertJsonFragment([
            'empty' => true
        ]);
    }
}
