<?php

namespace Tests\Feature\User;

use App\Models\User;
use Tests\TestCase;

class UserRegisterTest extends TestCase
{
    /** @test */
    function name_is_required()
    {
        $this->postJson(route('auth.register.store'), [
            'name' => ''
        ])
            ->assertJsonValidationErrors(['name']);
    }

    /** @test */
    function email_is_required()
    {
        $this->postJson(route('auth.register.store'), [
            'email' => ''
        ])
            ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    function emails_must_be_valid()
    {
        $this->postJson(route('auth.register.store'), [
            'email' => 'invalid-email'
        ])
            ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    function emails_must_be_unique()
    {
        factory(User::class)->create([
            'email' => 'example@mail.com'
        ]);

        $this->postJson(route('auth.register.store'), [
            'email' => 'example@mail.com'
        ])
            ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    function password_is_required()
    {
        $this->postJson(route('auth.register.store'), [
            'password' => ''
        ])
            ->assertJsonValidationErrors(['password']);
    }

    /** @test */
    function user_can_be_registered_with_email_name_and_password()
    {
        $this->postJson(route('auth.register.store'), [
            'email' => 'example@mail.com',
            'name' => 'John',
            'password' => 'secret',
        ]);

        tap(User::get(), function ($users) {
            $this->assertCount(1, $users);
            $this->assertEquals('John', $users[0]->name);
            $this->assertEquals('example@mail.com', $users[0]->email);
        });
    }

    /** @test */
    function user_resource_returns_on_register_request()
    {
        $response = $this->postJson(route('auth.register.store'), [
            'email' => 'example@mail.com',
            'name' => 'John',
            'password' => 'secret',
        ]);

        $response->assertJsonFragment([
            'email' => 'example@mail.com',
        ]);
    }
}
