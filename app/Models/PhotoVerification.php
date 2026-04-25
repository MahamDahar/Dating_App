<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhotoVerification extends Model
{
    protected $table = 'photo_verifications';

    protected $fillable = [
        'user_id',
        'status',
        'provider',
        'score',
        'selfie_path',
        'reason',
        'provider_payload',
        'reviewed_at',
        'reviewed_by',
    ];

    protected $casts = [
        'provider_payload' => 'array',
        'reviewed_at'      => 'datetime',
        'score'            => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function getSelfieUrlAttribute(): ?string
    {
        if (!$this->selfie_path) {
            return null;
        }
        return asset('storage/' . ltrim($this->selfie_path, '/'));
    }
}

