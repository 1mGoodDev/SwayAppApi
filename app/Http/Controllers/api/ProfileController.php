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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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

    public function updateProfile(Request $request)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'name'          => 'nullable|string',
            'job'           => 'nullable|string',
            'bio'           => 'nullable|string',
            'profile_picture'   =>  'nullable|image|mimes:jpeg,jpg,png,svg|max:2048',
            'background_image'  =>  'nullable|image|mimes:jpeg,jpg,png,svg|max:2048',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //find product by ID
        $user = Auth::user();

        //check if image is not empty
        if ($request->hasFile('background_image')) {

            //delete old image
            Storage::delete('background_image/' . basename($user->image));

            //upload image
            $image = $request->file('background_image');
            $image->storeAs('background_image', $image->hashName());

            //update product with new image
            $user->update([
                'name'         => $request->name,
                'job'         => $request->job,
                'bio'   => $request->bio,
                'background_image'         => $image->hashName(),
            ]);

        } else {

            //update product without image
            $user->update([
                'name'         => $request->name,
                'job'         => $request->job,
                'bio'   => $request->bio,
            ]);
        }

        //return response
        return new ApiResource(true, 'Data Profile Berhasil Diubah!', $user);
    }
}
