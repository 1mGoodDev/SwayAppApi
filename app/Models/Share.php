<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Share extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
    ];

    // Relasi: Share ini dilakukan oleh siapa
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Share ini berasal dari post mana
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
