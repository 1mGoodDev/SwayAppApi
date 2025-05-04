<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
    ];

    // Relasi: Like ini diberikan oleh siapa
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Like ini diberikan ke post mana
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
