<?php

namespace Tests\Feature\Orders;

use App\Models\Address;
use App\Models\ProductVariation;
use App\Models\ShippingMethod;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

class OrderStoreTest extends TestCase
{
    /** @test */
    function it_fails_if_not_authenticated()
    {
        $this->postJson('api/orders')->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    function it_requires_an_address()
    {
        $this->signIn()->postJson('api/orders')->assertJsonValidationErrors('address_id');
    }

    /** @test */
    function it_requires_an_existing_address()
    {
        $this->signIn()
            ->postJson('api/orders', [
                'address_id' => 2,
            ])
            ->assertJsonValidationErrors('address_id');
    }


    /** @test */
    function it_requires_an_address_that_belongs_to_the_user()
    {
        $address = factory(Address::class)->create([
            'user_id' => factory(User::class)->create(),
        ]);

        $this->signIn()
            ->postJson('api/orders', [
                'address_id' => $address->id,
            ])
            ->assertJsonValidationErrors('address_id');
    }

    /** @test */
    function it_requires_a_shipping_method()
    {
        $this->signIn()->postJson('api/orders')->assertJsonValidationErrors('shipping_method_id');
    }

    /** @test */
    function it_requires_a_shipping_method_that_exists()
    {
        $this->signIn()
            ->postJson('api/orders', [
                'shipping_method_id' => 1
            ])
            ->assertJsonValidationErrors('shipping_method_id');
    }

    /** @test */
    function it_requires_a_valid_shipping_method_for_the_given_address()
    {
        $user = factory(User::class)->create();

        $address = factory(Address::class)->create([
            'user_id' => $user->id,
        ]);

        $shipping = factory(ShippingMethod::class)->create();

        $this->signIn()
            ->postJson('api/orders', [
                'shipping_method_id' => $shipping->id,
                'address_id' => $address->id,
            ])
            ->assertJsonValidationErrors('shipping_method_id');
    }

    /** @test */
    function it_can_create_an_order()
    {
        $user = factory(User::class)->create();

        [$address, $shipping] = $this->orderDependencies($user);

        $response = $this->signIn($user)->post('api/orders', [
            'address_id' => $address->id,
            'shipping_method_id' => $shipping->id
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'address_id' => $address->id,
            'shipping_method_id' => $shipping->id,
        ]);
    }

    /** @test */
    function it_attaches_the_products_to_the_order()
    {
        $user = factory(User::class)->create();

        $user->cart()->sync(
            $product = $this->productWithStock()
        );

        [$address, $shipping] = $this->orderDependencies($user);

        $response = $this->signIn($user)->post('api/orders', [
            'address_id' => $address->id,
            'shipping_method_id' => $shipping->id
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('product_variation_order', [
            'product_variation_id' => $product->id,
            'order_id' => $response->json()['id']
        ]);
    }

    /** @test */
    function it_fails_to_create_an_order_if_cart_is_empty()
    {
        $user = factory(User::class)->create();

        $product = $this->productWithStock();

        $user->cart()->sync([
            $product->id => [
                'quantity' => 0
            ],
        ]);

        [$address, $shipping] = $this->orderDependencies($user);

        $response = $this->signIn($user)->post('api/orders', [
            'address_id' => $address->id,
            'shipping_method_id' => $shipping->id
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $this->assertDatabaseMissing('product_variation_order', [
            'product_variation_id' => $product->id,
        ]);
    }

    protected function productWithStock()
    {
        $product = factory(ProductVariation::class)->create();

        factory(Stock::class)->create([
            'product_variation_id' => $product->id,
        ]);

        return $product;
    }

    protected function orderDependencies(User $user)
    {
        $address = factory(Address::class)->create([
            'user_id' => $user->id,
        ]);

        $shipping = factory(ShippingMethod::class)->create();

        $shipping->countries()->attach($address->country);

        return [$address, $shipping];
    }
}
