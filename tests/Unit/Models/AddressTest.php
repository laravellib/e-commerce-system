<?php

namespace Tests\Unit\Models;

use App\Models\Address;
use App\Models\Country;
use Tests\TestCase;

class AddressTest extends TestCase
{
    /** @test */
    function it_belongs_to_country()
    {
        $address = factory(Address::class)->create();

        $this->assertInstanceOf(Country::class, $address->country);
    }
}
