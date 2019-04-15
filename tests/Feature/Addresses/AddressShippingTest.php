<?php

namespace Tests\Feature\Addresses;

use App\Models\Address;
use App\Models\Country;
use App\Models\ShippingMethod;
use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

class AddressShippingTest extends TestCase
{
    /** @test */
    function it_fails_if_not_authenticated()
    {
        $this->getJson('api/addresses/1/shipping')
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    function it_fails_if_the_address_cant_not_found()
    {
        $this->signIn()->getJson('api/addresses/1/shipping')
            ->assertNotFound();
    }

    /** @test */
    function it_fails_if_the_user_doesnt_own_the_address()
    {
        $address = factory(Address::class)->create();

        $this->signIn()->getJson("api/addresses/{$address->id}/shipping")
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    function it_shows_shipping_methods_for_the_given_address()
    {
        $user = factory(User::class)->create();
        $country = factory(Country::class)->create();
        $shipping = factory(ShippingMethod::class)->create();

        $country->shippingMethods()->attach($shipping);

        $address = factory(Address::class)->create([
            'user_id' => $user->id,
            'country_id' => $country->id,
        ]);

        $response = $this->signIn($user)->getJson("api/addresses/{$address->id}/shipping");

        $response->assertOk();
        $response->assertJsonFragment([
            'id' => $shipping->id,
        ]);
    }
}
