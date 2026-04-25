<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model {
    protected $fillable = ['liker_id', 'liked_id'];

    public function liker() {
        return $this->belongsTo(User::class, 'liker_id');
    }

    public function liked() {
        return $this->belongsTo(User::class, 'liked_id');
    }
}