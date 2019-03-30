<?php

namespace Tests\Unit\Models;

use App\Models\Address;
use App\Models\Country;
use App\Models\User;
use Tests\TestCase;

class AddressTest extends TestCase
{
    /** @test */
    function it_belongs_to_a_country()
    {
        $address = factory(Address::class)->create();

        $this->assertInstanceOf(Country::class, $address->country);
    }

    /** @test */
    function it_belongs_to_a_user()
    {
        $address = factory(Address::class)->create();

        $this->assertInstanceOf(User::class, $address->user);
    }

    /** @test */
    function it_sets_old_addresses_to_not_default_when_creating()
    {
        $user = factory(User::class)->create();

        $oldAddress = factory(Address::class)->create([
            'default' => true,
            'user_id' => $user->id,
        ]);

        factory(Address::class)->create([
            'default' => true,
            'user_id' => $user->id,
        ]);

        $this->assertFalse($oldAddress->fresh()->default);
    }
}
