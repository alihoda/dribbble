<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\Image;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->only(['update', 'destroy']);
    }

    public function index()
    {
        return UserResource::collection(User::paginate(5));
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
        // Check for image in request
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('avatars');
            $user->avatar()->save(Image::make(['path' => $path]));
        }

        return response()->json([
            'message' => 'Successful registered',
            'data' => $user,
            'avatar' => $user->avatar->url() ?? ''
        ]);
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        // Check if current user is 
        if (Auth::user()->id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $request->validated();
        $user->update($request->all());

        // Update user avatar if file is uploaded
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('avatars');
            if ($user->avatar) {
                Storage::delete($user->avatar->path);
                $user->avatar->path = $path;
                $user->avatar->save();
            } else {
                $user->avatar()->save(Image::make(['path' => $path]));
            }
        }

        return response()->json([
            'message' => 'Update successful',
            'data' => $user,
            'avatar' => $user->avatar->url()
        ]);
    }

    public function destroy(User $user)
    {
        // Check if current user is 
        if (Auth::user()->id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        Storage::delete($user->avatar->path);
        $user->avatar()->delete();
        $user->tokens()->delete();
        $user->delete();

        return response()->json(['message' => 'Delete successful']);
    }
}
