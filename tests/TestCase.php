<?php

namespace Tests;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    public function signIn(Authenticatable $user = null)
    {
        $user = $user ?: factory(User::class)->create();

        $token = JWTAuth::fromUser($user);

        $this->withHeader('Authorization', "Bearer ${token}");

        return $this;
    }
}
