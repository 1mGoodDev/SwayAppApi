<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function storeComment(Request $request, $id)
    {
        $request->validate(['content' => 'required|string']);

        $post = Post::findOrFail($id);
        $comment = $post->comments()->create([
            'user_id' => Auth::id(),
            'content' => $request->content
        ]);

        return new ApiResource(true, 'Berhasil mengirim comment', $comment);
    }

    public function getComments($id) {
        $posts = Post::findOrFail($id);
        $comments = $posts->comments()->with('user')->latest()->get();

        return new ApiResource(true, 'Berhasil mengambil comments', $comments);
    }
}
