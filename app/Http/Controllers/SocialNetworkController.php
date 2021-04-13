<?php

namespace App\Http\Controllers;

use App\Http\Resources\SocialResource;
use App\Models\SocialNetwork;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SocialNetworkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show']);
        $this->authorizeResource(SocialNetwork::class, 'socialNetwork');
    }

    public function index(User $user)
    {
        return SocialResource::collection(SocialNetwork::where('user_id', $user->id)->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'bail | required | string | min:5',
            'username' => 'bail | required | min:3',
        ]);

        $request['user_id'] = Auth::user()->id;
        $request['url'] = $this->url($request->only(['type', 'username']));

        $sn = SocialNetwork::create($request->all());

        return response()->json([
            'message' => $request['type'] . ' account created',
            'data' => $sn
        ]);
    }

    public function show(SocialNetwork $socialNetwork)
    {
        return new SocialResource($socialNetwork);
    }

    public function update(Request $request, SocialNetwork $socialNetwork)
    {
        $request->validate(['username' => 'required']);
        $request['url'] = $this->url($request->only(['type', 'username']));

        $socialNetwork->update($request->only(['username', 'url']));

        return response()->json([
            'message' => 'Updated successfully',
            'data' => $socialNetwork
        ]);
    }

    public function destroy(SocialNetwork $socialNetwork)
    {
        $socialNetwork->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }

    private function url($attributes)
    {
        switch ($attributes['type']) {
            case 'instagram':
                return 'https://www.instagram.com/' . $attributes['username'];
            case 'telegram':
                return 'https://t.me/' . $attributes['username'];
            case 'linkedin':
                return 'https://www.linkedin.com/in/' . $attributes['username'];
            default:
                return 'https://twitter.com/' . $attributes['username'];
        }
    }
}
