<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\JWTGuard;

class MeTest extends TestCase
{
    /** @test */
    function it_fails_if_user_isnt_authenticated()
    {
        $this->getJson(route('auth.me.index'))
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    function it_returns_a_user_details()
    {
        $user = factory(User::class)->create([
            'email' => 'example@mail.com',
            'password' => 'secret',
        ]);

        $this->signIn($user);

        $response = $this->getJson(route('auth.me.index'));

        $response->assertJsonFragment([
            'email' => 'example@mail.com',
        ]);
    }
}
