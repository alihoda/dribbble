<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{

    public function index()
    {
        //
    }

    public function store(UserStoreRequest $request)
    {
        $request->validated();
        $request['password'] = Hash::make($request['password']);
        $request['remember_token'] = Str::random(10);
        $user = User::create($request->all());

        if (is_null($user)) {
            return response()->json(['message' => 'Failed registered'], 422);
        }
        return response()->json([
            'message' => 'Successful registered',
            'data' => $user
        ]);
    }

    public function show(User $user)
    {
        //
    }

    public function update(Request $request, User $user)
    {
        //
    }

    public function destroy(User $user)
    {
        //
    }
}
