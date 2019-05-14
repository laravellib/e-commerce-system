<?php

namespace Tests\Unit\Models;

use App\Models\PaymentMethod;
use App\Models\User;
use Tests\TestCase;

class PaymentMethodTest extends TestCase
{
    /** @test */
    function it_sets_old_payment_methods_to_not_default_when_creating()
    {
        $user = factory(User::class)->create();

        $oldMethod = factory(PaymentMethod::class)->create([
            'default' => true,
            'user_id' => $user->id,
        ]);

        $newMethod = factory(PaymentMethod::class)->create([
            'default' => true,
            'user_id' => $user->id
        ]);

        $this->assertFalse($oldMethod->fresh()->default);
        $this->assertTrue($newMethod->fresh()->default);
    }

    /** @test */
    function it_belongs_to_a_user()
    {
        $method = factory(PaymentMethod::class)->create();

        $this->assertInstanceOf(User::class, $method->user);
    }
}
