<?php

// ── ContentFlag Model ──
// Save as: app/Models/ContentFlag.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentFlag extends Model
{
    protected $fillable = [
        'user_id', 'type', 'content',
        'photo_path', 'status', 'admin_note'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}