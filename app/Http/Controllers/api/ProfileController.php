<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResource;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\User;
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
            'background_image' => $user->background_image,
            'job' => $user->job,
            'bio' => $user->bio,
            'stats' => [
                'total_posts' => $allPosts->count(),
                'followers' => DB::table('follows')->where('following_id', $user->id)->count(),
                'following' => DB::table('follows')->where('follower_id', $user->id)->count(),
            ],
            'all_posts' => PostResource::collection($allPosts),
            'media_posts' => PostResource::collection($mediaPosts),
        ];

        return new ApiResource(true, 'Data berhasil diambil', $data);
    }

    public function otherProfile($name) {
        $user = User::where('name', $name)->first();

        if (!$user) {
            return new ApiResource(false, 'User tidak ditemukan', null);
        }

        $allPosts = Post::where('user_id', $user->id)
            ->with(['likes', 'comments'])
            ->latest()
            ->get();

        $mediaPosts = $allPosts->filter(fn($post) => $post->image !== null);

        $data = [
            'name' => $user->name,
            'profile_picture' => $user->profile_picture,
            'background_image' => $user->background_image,
            'job' => $user->job,
            'bio' => $user->bio,
            'stats' => [
                'total_posts' => $allPosts->count(),
                'followers' => DB::table('follows')->where('following_id', $user->id)->count(),
                'following' => DB::table('follows')->where('follower_id', $user->id)->count(),
            ],
            'all_posts' => PostResource::collection($allPosts),
            'media_posts' => PostResource::collection($mediaPosts),
        ];

        return new ApiResource(true, 'Data berhasil diambil', $data);
    }

    public function updateProfile(Request $request) {
        $user = Auth::user();

        if (!$user) {
            return new ApiResource(false, 'User not authenticated', null);
        }

        // $user->name = $request->name;
        // $user->job = $request->job;
        // $user->profile_picture = $request->profile_picture;
        // $user->background_image = $request->background_image;
        // $user->bio = $request->bio;

        $validated = $request->validate([
            'name' => 'nullable|string',
            'job' => 'nullable|string',
            'profile_picture' => 'nullable|image|max:2048',
            'background_image' => 'nullable|image|max:2048',
            'bio' => 'nullable|string',
        ]);

        if($request->hasFile('profile_picture')) {
            $validated['profile_picture'] = $request->file('profile_picture')->store('profile_picture', 'public');
        }

        if($request->hasFile('background_image')) {
            $validated['background_image'] = $request->file('background_image')->store('background_image', 'public');
        }

        $user->update($validated);
        return new ApiResource(true, 'Update is Success', $user);
    }
}
