<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proposal extends Model {
    protected $fillable = [
        'sender_id', 'receiver_id', 'message', 'status', 'accepted_at', 'rejected_at'
    ];

    protected $casts = [
        'accepted_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function sender() {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver() {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function isPending()  { return $this->status === 'pending';  }
    public function isAccepted() { return $this->status === 'accepted'; }
    public function isRejected() { return $this->status === 'rejected'; }

    /** Accepted proposal between two users, either direction. */
    public static function hasAcceptedBetween(int $userIdA, int $userIdB): bool
    {
        return static::where('status', 'accepted')
            ->where(function ($q) use ($userIdA, $userIdB) {
                $q->where(fn ($q2) => $q2->where('sender_id', $userIdA)->where('receiver_id', $userIdB))
                    ->orWhere(fn ($q2) => $q2->where('sender_id', $userIdB)->where('receiver_id', $userIdA));
            })
            ->exists();
    }
}