<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatRequest extends Model
{
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'status',
        'responded_at',
    ];

    protected $casts = [
        'responded_at' => 'datetime',
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /** Any pending chat request between two user ids (either direction). */
    public static function pendingBetween(int $userIdA, int $userIdB): ?self
    {
        return static::query()
            ->where('status', 'pending')
            ->where(function ($q) use ($userIdA, $userIdB) {
                $q->where(function ($q2) use ($userIdA, $userIdB) {
                    $q2->where('sender_id', $userIdA)->where('receiver_id', $userIdB);
                })->orWhere(function ($q2) use ($userIdA, $userIdB) {
                    $q2->where('sender_id', $userIdB)->where('receiver_id', $userIdA);
                });
            })
            ->first();
    }
}
