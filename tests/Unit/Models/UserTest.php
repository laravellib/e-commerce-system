<?php

namespace Tests\Unit\Models;

use App\Models\Address;
use App\Models\ProductVariation;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    /** @test */
    function it_hashes_the_password_when_user_creating()
    {
        $user = factory(User::class)->create([
            'password' => 'secret'
        ]);

        $this->assertTrue(Hash::check('secret', $user->password));
    }

    /** @test */
    function it_hashes_the_password_when_creating_with_mocking_hasher()
    {
        $hasher = Hash::shouldReceive('driver')->andReturnSelf()->getMock();
        $hasher->shouldReceive('make')->withArgs(['secret', []])->andReturn('hash-password');

        $user = factory(User::class)->create([
            'password' => 'secret'
        ]);

        $this->assertEquals('hash-password', $user->password);
    }

    /** @test */
    function it_has_many_cart_products()
    {
        $user = factory(User::class)->create();

        $user->cart()->attach(
            factory(ProductVariation::class)->create()->id
        );

        $this->assertInstanceOf(ProductVariation::class, $user->cart->first());
    }

    /** @test */
    function it_has_a_quantity_for_each_cart_products()
    {
        $user = factory(User::class)->create();

        $user->cart()->attach(
            factory(ProductVariation::class)->create()->id, [
                'quantity' => 5
            ]
        );

        $this->assertEquals(5, $user->cart->first()->pivot->quantity);
    }

    /** @test */
    function it_has_many_addresses()
    {
        $user = factory(User::class)->create();

        $user->addresses()->save(
            factory(Address::class)->make()
        );

        $this->assertInstanceOf(Address::class, $user->addresses->first());
    }
}
