<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'content',
        'image',
    ];

    // Relationship ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship ke comments
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Relationship ke likes
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // Relationship ke shares
    public function shares()
    {
        return $this->hasMany(Share::class);
    }

    // Hitung jumlah likes
    public function likeCount()
    {
        return $this->likes()->count();
    }

    // Hitung jumlah comments
    public function commentCount()
    {
        return $this->comments()->count();
    }
}
