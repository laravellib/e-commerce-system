<?php

namespace Tests\Feature\Countries;

use App\Models\Country;
use Tests\TestCase;

class CountryIndexTest extends TestCase
{
    /** @test */
    function it_returns_a_collection_of_countries()
    {
        $countries = factory(Country::class, 2)->create();

        $response = $this->getJson('api/countries');

        $response->assertJsonFragment([
            'id' => $countries[0]->id,
        ]);

        $response->assertJsonFragment([
            'id' => $countries[1]->id,
        ]);
    }
}
