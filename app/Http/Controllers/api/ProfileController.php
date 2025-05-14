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
use Illuminate\Support\Str;
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

        $data = [
            'name'  =>  $request->name,
            'job'  =>  $request->job,
            'bio'  =>  $request->bio,
        ];

        //check if image is not empty
        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                Storage::disk('public')->delete('profile_pictures/'.$user->profile_picture);
            }
            $file = $request->file('profile_picture');
            $filename = Str::random(20) . '.' . $file->extension();
            $file->storeAs('profile_pictures', $filename, 'public');
            $data['profile_picture'] = $filename;
        }

        //check if image is not empty
        if ($request->hasFile('background_image')) {
            if ($user->background_image) {
                Storage::disk('public')->delete('background_images/'.$user->background_image);
            }
            $file = $request->file('background_image');
            $filename = Str::random(20) . '.' . $file->extension();
            $file->storeAs('background_images', $filename, 'public');
            $data['background_image'] = $filename;
        }

        $user->save($data);

        // Kalau ingin langsung mengirim URL lengkap:
        $user->profile_picture_url    = $user->profile_picture
            ? asset('storage/profile_pictures/' . $user->profile_picture)
            : null;
        $user->background_image_url   = $user->background_image
            ? asset('storage/background_images/' . $user->background_image)
            : null;

        //return response
         try {
        // ... semua kode validasi, upload, update, dll. ...
            return new ApiResource(true, 'Data Profile Berhasil Diubah!', $user);
         } catch (\Throwable $e) {
        // kirim balik pesan error dan lokasi di kode
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ], 500);
    }
    }
}
