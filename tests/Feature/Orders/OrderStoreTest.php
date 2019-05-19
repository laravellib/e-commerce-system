<?php

namespace Tests\Feature\Orders;

use App\Events\Order\OrderCreated;
use App\Models\Address;
use App\Models\PaymentMethod;
use App\Models\ProductVariation;
use App\Models\ShippingMethod;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;
use Event;

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
        $user = factory(User::class)->create();

        $user->cart()->sync(
            $product = $this->productWithStock()
        );

        $this->signIn($user)->postJson('api/orders')
            ->assertJsonValidationErrors('address_id');
    }

    /** @test */
    function it_requires_an_existing_address()
    {
        $user = factory(User::class)->create();

        $user->cart()->sync(
            $product = $this->productWithStock()
        );

        $response = $this->signIn($user)->postJson('api/orders', [
            'address_id' => 2,
        ]);

        $response->assertJsonValidationErrors('address_id');
    }

    /** @test */
    function it_requires_an_address_that_belongs_to_the_user()
    {
        $user = factory(User::class)->create();

        $user->cart()->sync(
            $product = $this->productWithStock()
        );

        $address = factory(Address::class)->create();

        $response = $this->signIn($user)->postJson('api/orders', [
            'address_id' => $address->id,
        ]);

        $response->assertJsonValidationErrors('address_id');
    }

    /** @test */
    function it_requires_a_shipping_method()
    {
        $user = factory(User::class)->create();

        $user->cart()->sync(
            $product = $this->productWithStock()
        );

        $this->signIn($user)->postJson('api/orders')
            ->assertJsonValidationErrors('shipping_method_id');
    }

    /** @test */
    function it_requires_a_shipping_method_that_exists()
    {
        $user = factory(User::class)->create();

        $user->cart()->sync(
            $product = $this->productWithStock()
        );

        $response = $this->signIn($user)->postJson('api/orders', [
            'shipping_method_id' => 1
        ]);

        $response->assertJsonValidationErrors('shipping_method_id');
    }

    /** @test */
    function it_requires_a_valid_shipping_method_for_the_given_address()
    {
        $user = factory(User::class)->create();

        $address = factory(Address::class)->create([
            'user_id' => $user->id,
        ]);

        $shipping = factory(ShippingMethod::class)->create();

        $user->cart()->sync(
            $product = $this->productWithStock()
        );

        $response = $this->signIn($user)->postJson('api/orders', [
            'shipping_method_id' => $shipping->id,
            'address_id' => $address->id,
        ]);

        $response->assertJsonValidationErrors('shipping_method_id');
    }

    /** @test */
    function it_requires_a_payment_method()
    {
        $user = factory(User::class)->create();

        $user->cart()->sync(
            $product = $this->productWithStock()
        );

        $this->signIn($user)->postJson('api/orders')
            ->assertJsonValidationErrors('payment_method_id');
    }

    /** @test */
    function it_requires_a_payment_method_that_belongs_to_the_user()
    {
        $user = factory(User::class)->create();

        $user->cart()->sync(
            $product = $this->productWithStock()
        );

        $payment = factory(PaymentMethod::class)->create();

        $response = $this->signIn($user)->postJson('api/orders', [
            'payment_method_id' => $payment->id,
        ]);

        $response->assertJsonValidationErrors('payment_method_id');
    }

    /** @test */
    function it_can_create_an_order()
    {
        $user = factory(User::class)->create();

        $user->cart()->sync(
            $product = $this->productWithStock()
        );

        [$address, $shipping, $payment] = $this->orderDependencies($user);

        $response = $this->signIn($user)->post('api/orders', [
            'address_id' => $address->id,
            'shipping_method_id' => $shipping->id,
            'payment_method_id' => $payment->id,
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'address_id' => $address->id,
            'shipping_method_id' => $shipping->id,
            'payment_method_id' => $payment->id,
        ]);
    }

    /** @test */
    function it_attaches_the_products_to_the_order()
    {
        $user = factory(User::class)->create();

        $user->cart()->sync(
            $product = $this->productWithStock()
        );

        [$address, $shipping, $payment] = $this->orderDependencies($user);

        $response = $this->signIn($user)->post('api/orders', [
            'address_id' => $address->id,
            'shipping_method_id' => $shipping->id,
            'payment_method_id' => $payment->id,
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertDatabaseHas('product_variation_order', [
            'product_variation_id' => $product->id,
            'order_id' => $response->json('data.id'),
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

    /** @test */
    function it_fires_an_order_created_event()
    {
        Event::fake();

        $user = factory(User::class)->create();

        $user->cart()->sync(
            $product = $this->productWithStock()
        );

        [$address, $shipping, $payment] = $this->orderDependencies($user);

        $response = $this->signIn($user)->post('api/orders', [
            'address_id' => $address->id,
            'shipping_method_id' => $shipping->id,
            'payment_method_id' => $payment->id,
        ]);

        Event::assertDispatched(OrderCreated::class, function (OrderCreated $event) use ($response) {
            return $event->order->id === $response->json('data.id');
        });
    }

    /** @test */
    function it_empties_the_cart_when_ordering()
    {
        $user = factory(User::class)->create();

        $user->cart()->sync(
            $product = $this->productWithStock()
        );

        [$address, $shipping, $payment] = $this->orderDependencies($user);

        $this->signIn($user)->post('api/orders', [
            'address_id' => $address->id,
            'shipping_method_id' => $shipping->id,
            'payment_method_id' => $payment->id,
        ]);

        $this->assertEmpty($user->cart);
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
        $stripeCustomer = \Stripe\Customer::create([
            'email' => $user->email,
        ]);

        $user->update([
            'customer_id' => $stripeCustomer->id,
        ]);

        $address = factory(Address::class)->create([
            'user_id' => $user->id,
        ]);

        $shipping = factory(ShippingMethod::class)->create();

        $shipping->countries()->attach($address->country);

        $payment = factory(PaymentMethod::class)->create([
            'user_id' => $user->id,
        ]);

        return [$address, $shipping, $payment];
    }
}
