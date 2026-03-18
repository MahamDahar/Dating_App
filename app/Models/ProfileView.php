<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfileView extends Model
{
    protected $fillable = [
        'viewer_id',
        'viewed_id',
        'seen',
        'viewed_at',
    ];

    protected $casts = [
        'seen'      => 'boolean',
        'viewed_at' => 'datetime',
    ];

    // ── Relationships ─────────────────────────────────────────

    // The person who viewed the profile
    public function viewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'viewer_id');
    }

    // The person whose profile was viewed
    public function viewed(): BelongsTo
    {
        return $this->belongsTo(User::class, 'viewed_id');
    }
}