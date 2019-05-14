<?php

namespace Tests\Feature\PaymentMethods;

use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

class PaymentMethodIndexTest extends TestCase
{
    /** @test */
    function it_fails_if_not_authenticated()
    {
        $this->getJson('api/payment-methods')->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    function it_returns_a_collection_of_payment_methods()
    {
        $user = factory(User::class)->create();

        $payments = factory(PaymentMethod::class, 2)->create([
            'user_id' => $user->id
        ]);

        $response = $this->signIn($user)->getJson('api/payment-methods');

        $response->assertJsonFragment([
            'id' => $payments[0]->id,
        ]);

        $response->assertJsonFragment([
            'id' => $payments[1]->id,
        ]);
    }
}
