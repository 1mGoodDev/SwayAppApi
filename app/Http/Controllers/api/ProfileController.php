<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResource;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function myProfile()
    {
        $user = Auth::user();

        if (!$user) {
            return new ApiResource(false, 'User not authenticated', null);
        }

        $allPosts = Post::where('user_id', $user->id)
            ->with(['likes', 'comments'])
            ->latest()
            ->get();

        $mediaPosts = $allPosts->filter(fn($post) => $post->image !== null);

        $data = [
            'name' => $user->name,
            'profile_picture' => $user->profile_picture,
            'job' => $user->job,
            'bio' => $user->bio,
            'stats' => [
                'total_posts' => $allPosts->count(),
                'followers' => DB::table('follows')->where('following_id', $user->id)->count(),
                'following' => DB::table('follows')->where('follower_id', $user->id)->count(),,
            ],
            'all_posts' => PostResource::collection($allPosts),
            'media_posts' => PostResource::collection($mediaPosts),
        ];

        return new ApiResource(true, 'Data berhasil diambil', $data);
    }


}
