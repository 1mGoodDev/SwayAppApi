<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResource;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->with(['user', 'likes', 'comments'])->get();
        return new ApiResource(true, 'Post berhasil diambil', PostResource::collection($posts));
    }

    public function following()
    {
        $user = Auth::user();

        $followingIds = DB::table('follows')
            ->where('follower_id', $user->id)
            ->pluck('following_id');

        $posts = Post::whereIn('user_id', $followingIds)
            ->with(['user', 'likes', 'comments'])
            ->latest()
            ->get();

        return new ApiResource(true, 'Post - following berhasil diambil', PostResource::collection($posts));

    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'nullable|string',
            'image' => 'nullable|image|max:20p48'
        ]);

        $validated['user_id'] = Auth::id();

        if($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('posts', 'public');
        }

        $posts = Post::create($validated);
        return new ApiResource(true, 'berhasil posting', new PostResource($posts));
    }

    public function show(string $id)
    {
        $post = Post::with(['user', 'likes', 'comments'])->findOrFail($id);

        return new ApiResource(true, 'Detail post berhasil diambil', new PostResource($post));
    }

    public function update(Request $request, string $id)
    {
        $post = Post::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'content' => 'nullable|string',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }

            $validated['image'] = $request->file('image')->store('post', 'public');
        }

        $post->update($validated);

        return new ApiResource(true, 'Post berhasil di update', new PostResource($post));
    }

    public function destroy(string $id)
    {
        $post = Post::where('user_id', Auth::id())->findOrFail($id);

        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return new ApiResource(true, 'Post berhasil dihapus');
    }
}
