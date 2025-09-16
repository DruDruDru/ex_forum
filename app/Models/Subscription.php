<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'subscriber_id', 'creator_id'
    ];

    public $timestamps = false;

    protected static function boot()
    {
        self::creating(fn($grade) => $grade->created_at = now());
        parent::boot();
    }
}
