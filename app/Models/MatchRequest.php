<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class MatchRequest extends Model
{
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'status',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /** Messaging / calls allowed only after chat request was accepted (stored here as accepted). */
    public static function messagingUnlockedBetween(int $userIdA, int $userIdB): bool
    {
        return static::where('status', 'accepted')
            ->where(function ($q) use ($userIdA, $userIdB) {
                $q->where(fn ($q2) => $q2->where('sender_id', $userIdA)->where('receiver_id', $userIdB))
                    ->orWhere(fn ($q2) => $q2->where('sender_id', $userIdB)->where('receiver_id', $userIdA));
            })
            ->exists();
    }
}