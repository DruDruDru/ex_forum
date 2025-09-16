<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Grade extends Model
{
    protected $fillable = [
        'rating', 'user_id', 'post_id'
    ];

    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = ['user_id', 'post_id'];

    protected static function boot()
    {
        self::creating(fn($grade) => $grade->created_at = now());

        parent::boot();
    }
}
