<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserLoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        // Try to authenticate the user
        if (!Auth::attempt($request->only(['username', 'password']))) {
            return response()->json(['message' => 'Invalid credentials'], 422);
        }
        // Retrieve user based on given username
        $user = User::where('username', $request['username'])->first();
        // Remove previous token
        $user->tokens()->delete();
        // Create new token
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => new UserResource($user),
            'token' => $token
        ]);
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();
        return response()->json(['message' => 'Logout successful']);
    }
}
