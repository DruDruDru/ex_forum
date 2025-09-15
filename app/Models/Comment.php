<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'content',
        'user_id',
        'post_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public static function posses($id): bool
    {
        if (preg_match('/^[0-9]*$/', $id) &&
            static::where('id', $id)->exists()) {
            return true;
        }
        return false;
    }

    protected static function boot()
    {
        self::creating(fn($comment) => $comment->updated_at = null);

        parent::boot();
    }
}
