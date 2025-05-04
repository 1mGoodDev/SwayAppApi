<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens ,HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'status',
        'password',
        'profile_picture',
        'job',
        'bio'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }


    public function canAccessPanel(Panel $panel): bool
    {
        return str_ends_with($this->email, '@admin.com'); //&& $this->hasVerifiedEmail();
    }

    // Relationship ke posts
    public function posts()
    {
        return $this->hasMany(Post::class);
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

    // Relationship ke search_history
    public function searchHistories()
    {
        return $this->hasMany(SearchHistories::class);
    }

    // Cek apakah sudah follow user lain
    public function isFollowing($userId)
    {
        return $this->followings()->where('following_id', $userId)->exists();
    }

    // Follow user lain
    public function follow($userId)
    {
        if (!$this->isFollowing($userId) && $this->id !== $userId) {
            return $this->followings()->attach($userId);
        }
    }


    // User yang aku follow
    public function followings()
{
    return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id');
}

    // User yang follow aku
    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id');
    }

    // Unfollow user lain
    public function unfollow($userId)
    {
        return $this->followings()->detach($userId);
    }
}
