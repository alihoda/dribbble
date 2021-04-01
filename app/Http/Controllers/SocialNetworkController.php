<?php

namespace App\Http\Controllers;

use App\Http\Resources\SocialResource;
use App\Models\SocialNetwork;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SocialNetworkController extends Controller
{

    public function index(User $user)
    {
        return SocialResource::collection(SocialNetwork::where('user_id', $user->id)->get());
    }

    public function store(Request $request)
    {
        // validate request data
        $request->validate([
            'type' => 'bail | required | string | min:5',
            'username' => 'bail | required | min:3',
        ]);
        // create url based on given type
        $request['url'] = $this->generate_url($request['type'], $request['username']);
        // set authenticated user's id to user_id
        $request['user_id'] = Auth::user()->id;
        // create new model
        $sn = SocialNetwork::create($request->all());

        return response()->json([
            'message' => $request['type'] . ' account created',
            'data' => $sn
        ]);
    }

    public function show(SocialNetwork $socialNetwork)
    {
        return new SocialResource($socialNetwork);
        // return response()->json(['url' => $socialNetwork->url]);
    }

    public function update(Request $request, SocialNetwork $socialNetwork)
    {
        // check user authorization
        $this->authorize('update', $socialNetwork);
        // validate request data
        $request->validate(['username' => 'required']);
        // update sn url
        $request['url'] = $this->generate_url($socialNetwork->type, $request['username']);
        // update model
        $socialNetwork->update($request->only(['username', 'url']));

        return response()->json([
            'message' => 'Updated successfully',
            'data' => $socialNetwork
        ]);
    }

    public function destroy(SocialNetwork $socialNetwork)
    {
        // check user authorization
        $this->authorize('delete', $socialNetwork);
        // delete model
        $socialNetwork->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }

    private function generate_url($type, $username)
    {
        switch ($type) {
            case 'instagram':
                return 'https://www.instagram.com/' . $username;
            case 'telegram':
                return 'https://t.me/' . $username;
            case 'linkedin':
                return 'https://www.linkedin.com/in/' . $username;
            default:
                return 'https://twitter.com/' . $username;
        }
    }
}
