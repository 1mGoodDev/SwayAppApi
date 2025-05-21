<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResource;
use App\Models\Follow;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FollowController extends Controller
{
    public function follow(Request $request) {
        $request->validate([
            'user_id'   =>  'required|exists:users,id'
        ]);

        if($request->user_id == $request->user()->id) {
            return response()->json([
                'status'    =>  'error',
                'message'   =>  'Can not follow yourself'
            ]);
        }

        $follow = Follow::where('follower_id', $request->user()->id)
            ->where('following_id', $request->user_id)
            ->first();

        if($follow) {
            $follow->delete();

            return response()->json([
                'status'    =>  'success',
                'message'   =>  'Unfollow user'
            ]);
        } else {
            Follow::create([
                'follower_id'   =>  $request->user()->id,
                'following_id'  =>  $request->user_id
            ]);

            return response()->json([
                'status'    =>  'success',
                'message'   =>  'Success to Follow'
            ]);
        }
    }
}
