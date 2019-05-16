<?php

namespace Tests\Feature\PaymentMethods;

use Illuminate\Http\Response;
use Tests\TestCase;

class PaymentMethodStoreTest extends TestCase
{
    /** @test */
    function it_fails_if_not_authenticated()
    {
        $this->postJson('api/payment-methods')->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    function it_requires_a_token()
    {
        $this->signIn()->postJson('api/payment-methods')
            ->assertJsonValidationErrors(['token']);
    }

    /** @test */
    function it_can_successfully_add_a_card()
    {
        $this->signIn()->postJson('api/payment-methods', [
            'token' => 'tok_visa',
        ]);

        // TODO: hit stripe API and check 'provider_id'

        $this->assertDatabaseHas('payment_methods', [
            'user_id' => auth()->id(),
            'card_type' => 'Visa',
            'last_four' => '4242'
        ]);
    }

    /** @test */
    function it_returns_the_created_card()
    {
        $response = $this->signIn()->postJson('api/payment-methods', [
            'token' => 'tok_visa',
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonFragment([
            'card_type' => 'Visa',
        ]);
    }

    /** @test */
    function it_sets_the_created_card_as_default()
    {
        $response = $this->signIn()->postJson('api/payment-methods', [
            'token' => 'tok_visa',
        ]);

        $this->assertDatabaseHas('payment_methods', [
            'id' => $response->json('data.id'),
            'default' => true
        ]);
    }
}
