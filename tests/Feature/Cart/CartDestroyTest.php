<?php

namespace Tests\Feature\Cart;

use App\Models\ProductVariation;
use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

class CartDestroyTest extends TestCase
{
    /** @test */
    function it_fails_if_unauthenticated()
    {
        $this->deleteJson(route('cart.update', factory(ProductVariation::class)->create()))
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    function it_fails_if_the_product_cannot_be_found()
    {
        $this->signIn()->deleteJson(route('cart.update', 1))
            ->assertNotFound();
    }

    /** @test */
    function a_user_cart_product_can_be_deleted()
    {
        $user = factory(User::class)->create();
        $variation = factory(ProductVariation::class)->create();
        $user->cart()->attach($variation, ['quantity' => 1]);

        $this->signIn($user)->deleteJson(route('cart.update', $variation));

        $this->assertDatabaseMissing('cart_user', [
            'product_variation_id' => $variation->id,
            'user_id' => $user->id,
        ]);
    }
}
