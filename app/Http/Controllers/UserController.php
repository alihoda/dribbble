<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\Image;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->only(['update', 'destroy']);
    }

    public function index()
    {
        return Cache::tags('user')->remember('users', now()->addMinute(), function () {
            return UserResource::collection(User::all());
        });
    }

    public function store(UserStoreRequest $request)
    {
        $request->validated();
        $user = User::createUser($request);

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
            'data' => new UserResource($user),
        ]);
    }

    public function show($user)
    {
        return Cache::tags('user')
            ->remember("user-{$user}", now()->addMinute(), function () use ($user) {
                return new UserResource(User::with(['product', 'socialNetwork'])->findOrFail($user));
            });
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        // Check if current user is 
        if (Auth::user()->id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $request->validated();

        if ($request->hasFile('resume')) {
            $request['resume_path'] = $request->file('resume')->store('resumes');
        }

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

        Storage::delete($user->avatar->path);   // delete user's avatar from storage
        $user->avatar()->delete();              // delete user avatar
        $user->tokens()->delete();              // delete all user's tokens
        $user->delete();                        // delete user record

        return response()->json(['message' => 'Delete successful']);
    }
}
