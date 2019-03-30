<?php

namespace Tests\Feature\Addresses;

use App\Models\Country;
use Illuminate\Http\Response;
use Tests\TestCase;

class AddressStoreTest extends TestCase
{
    /** @test */
    function it_fails_if_authenticated()
    {
        $this->getJson('api/addresses')
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    function it_requires_a_name()
    {
        $this->signIn()->postJson('api/addresses')
            ->assertJsonValidationErrors(['name']);
    }

    /** @test */
    function it_requires_an_address_line_one()
    {
        $this->signIn()->postJson('api/addresses')
            ->assertJsonValidationErrors(['address_1']);
    }

    /** @test */
    function it_requires_a_city()
    {
        $this->signIn()->postJson('api/addresses')
            ->assertJsonValidationErrors(['city']);
    }

    /** @test */
    function it_requires_a_postal_code()
    {
        $this->signIn()->postJson('api/addresses')
            ->assertJsonValidationErrors(['postal_code']);
    }

    /** @test */
    function it_requires_a_country_id()
    {
        $this->signIn()->postJson('api/addresses')
            ->assertJsonValidationErrors(['country_id']);
    }

    /** @test */
    function it_requires_an_existing_country_id()
    {
        $this->signIn()->postJson('api/addresses', [
            'country_id' => 999
        ])
            ->assertJsonValidationErrors(['country_id']);
    }

    /** @test */
    function it_stores_an_address()
    {
        $this->signIn()->postJson('api/addresses', $payload = [
            'name' => 'John Doe',
            'address_1' => '123 Code Street',
            'city' => 'Kyiv',
            'postal_code' => 'CO1234',
            'country_id' => factory(Country::class)->create()->id,
        ]);

        $this->assertDatabaseHas('addresses', array_merge([
            'user_id' => auth()->id()
        ], $payload));
    }

    /** @test */
    function it_returns_an_address_when_created()
    {
        $response = $this->signIn()->postJson('api/addresses', [
            'name' => 'John Doe',
            'address_1' => '123 Code Street',
            'city' => 'Kyiv',
            'postal_code' => 'CO1234',
            'country_id' => factory(Country::class)->create()->id,
        ]);

        $response->assertJsonFragment([
            'id' => json_decode($response->getContent())->data->id,
        ]);
    }
}
