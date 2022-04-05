<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($request->password);
        $user = User::create($data);

        return new UserResource($user);
    }

    public function login(LoginRequest $request)
    {
        if (!auth()->attempt($request->only('email', 'password'))) {
            return response(['error' => 'Invalid credentials'], 401);
        }

        $token = auth()->user()->createToken('Personal Access Token')->accessToken;

        return response(
            [
                'user' => new UserResource(auth()->user()),
                'token' => $token,
            ],
            200
        );
    }
}
