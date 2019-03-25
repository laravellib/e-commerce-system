<?php

namespace Tests\Feature\Addresses;

use App\Models\Address;
use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

class AddressIndexTest extends TestCase
{
    /** @test */
    function it_fails_if_not_authenticated()
    {
        $this->getJson('api/addresses')
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    function it_shows_addresses_for_authenticated_user()
    {
        $user = factory(User::class)->create();

        $address = factory(Address::class)->create([
            'user_id' => $user->id
        ]);

        $response = $this->signIn($user)->getJson('api/addresses');

        $response->assertJsonFragment([
            'id' => $address->id,
            'name' => $address->name,
            'address_1' => $address->address_1,
            'city' => $address->city,
            'postal_code' => $address->postal_code,
            'country' => [
                'id' => $address->country->id,
                'code' => $address->country->code,
                'name' => $address->country->name,
            ]
        ]);
    }
}
