<?php

namespace Tests\Feature\Orders;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

class OrderIndexTest extends TestCase
{
    /** @test */
    function it_fails_if_user_is_not_authenticated()
    {
        $this->getJson('api/orders')->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    function it_returns_a_collection_of_orders()
    {
        $user = factory(User::class)->create();

        $order = factory(Order::class)->create([
            'user_id' => $user->id,
        ]);

        $this->signIn($user)->getJson('api/orders')
            ->assertJsonFragment([
                'id' => $order->id,
            ]);
    }

    /** @test */
    function it_orders_by_the_latest_first()
    {
        $user = factory(User::class)->create();

        $order = factory(Order::class)->create([
            'user_id' => $user->id,
            'created_at' => now()->subDay(),
        ]);

        $anotherOrder = factory(Order::class)->create([
            'user_id' => $user->id,
        ]);

        $this->signIn($user)->getJson('api/orders')
            ->assertSeeInOrder([
                $anotherOrder->created_at->toDateTimeString(),
                $order->created_at->toDateTimeString(),
            ]);
    }

    /** @test */
    function it_has_pagination()
    {
        $user = factory(User::class)->create();

        $this->signIn($user)->getJson('api/orders')
            ->assertJsonStructure([
                'links', 'meta'
            ]);
    }
}
