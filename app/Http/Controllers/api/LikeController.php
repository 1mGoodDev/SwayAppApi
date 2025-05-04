<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function like($id)
    {
        $user = Auth::user();
        $post = Post::findOrFail($id);

        //toggle like
        $like = $post->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            $message = "Tidak Disukai";
        } else {
            $post->likes()->create(['user_id' => $user->id]);
            $message = 'Disukai';
        }

        return new ApiResource(true, $message, $like);
    }
}
