<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FollowController extends Controller
{
    public function follow($id) {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return new ApiResource(false, 'Tidak bisa follow diri sendiri');
        }

        DB::table('follows')->updateOrInsert([
            'follower_id' => Auth::id(),
            'following_id' => $user->id,
        ]);

        return new ApiResource(true, 'Berhasil follow user');
    }

    public function unfollow($id) {
        $user = User::findOrFail($id);

        DB::table('follows')
            ->where('follower_id', Auth::id())
            ->where('following_id', $user->id)
            ->delete();

        return new ApiResource(true, 'Berhasil unfollow user');
    }
}
