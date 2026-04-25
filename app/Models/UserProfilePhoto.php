<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfilePhoto extends Model
{
    protected $table    = 'user_profile_photos';
    protected $fillable = ['user_id', 'path', 'is_main', 'is_blurred', 'order'];

    public function user() { return $this->belongsTo(User::class); }

    // Full URL accessor
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }
}