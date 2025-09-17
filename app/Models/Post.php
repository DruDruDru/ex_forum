<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    protected $fillable = [
        'title',
        'content',
        'user_id',
        'image_path'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Grade::class)->where('rating', 1);
    }

    public function dislikes()
    {
        return $this->hasMany(Grade::class)->where('rating', -1);
    }

    public function scopeSubscriptions($query)
    {
        $creatorIds = Subscription::where('subscriber_id', Auth::id())
                    ->pluck('creator_id')->toArray();
        return $query->whereIn('user_id', $creatorIds);
    }

    public function scopeLiked($query)
    {
        $postIds = Grade::where('rating', 1)
                        ->where('user_id', Auth::id())
                        ->pluck('post_id')->toArray();
        return $query->whereIn('id', $postIds);
    }

    public static function posses($id): bool
    {
        if (preg_match('/^[0-9]*$/', $id) &&
            static::where('id', $id)->exists()) {
            return true;
        }
        return false;
    }
}
