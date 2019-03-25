<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /** @test */
    function it_requires_an_email()
    {
        $this->postJson(route('auth.login.store'))
            ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    function it_requires_a_password()
    {
        $this->postJson(route('auth.login.store'))
            ->assertJsonValidationErrors(['password']);
    }

    /** @test */
    function it_returns_validation_error_if_credentials_dont_match()
    {
        factory(User::class)->create([
            'email' => 'example@mail.com',
            'password' => 'secret',
        ]);

        $response = $this->postJson(route('auth.login.store'), [
            'email' => 'example@mail.com',
            'password' => 'invalid-password',
        ]);

        $response->assertJsonValidationErrors(['email']);
    }

    /** @test */
    function it_returns_a_token_if_credentials_do_match()
    {
        factory(User::class)->create([
            'email' => 'example@mail.com',
            'password' => 'secret',
        ]);

        $response = $this->postJson(route('auth.login.store'), [
            'email' => 'example@mail.com',
            'password' => 'secret',
        ]);

        $response->assertJsonStructure([
            'meta' => ['token']
        ]);
    }

    /** @test */
    function it_returns_a_user_if_credentials_do_match()
    {
        factory(User::class)->create([
            'email' => 'example@mail.com',
            'password' => 'secret',
        ]);

        $response = $this->postJson(route('auth.login.store'), [
            'email' => 'example@mail.com',
            'password' => 'secret',
        ]);

        $response->assertJsonFragment([
            'email' => 'example@mail.com',
        ]);
    }
}
