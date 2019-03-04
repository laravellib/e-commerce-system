<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\PrivateUserResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class LoginController extends Controller
{
    public function store(LoginRequest $request)
    {
        $token = auth()->attempt($request->only(['email', 'password']));

        if (!$token) {
            return response()->json([
                'errors' => [
                    'email' => ['Could not sign you in with those details']
                ]
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return (new PrivateUserResource($request->user()))
            ->additional([
                'meta' => [
                    'token' => $token,
                ],
            ]);
    }
}
