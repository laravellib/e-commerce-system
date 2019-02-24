<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Hashing\BcryptHasher;
use Illuminate\Support\Facades\Hash;
use Money\Money;
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
}
