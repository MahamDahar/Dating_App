<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatWebrtcSignal extends Model
{
    protected $table = 'chat_webrtc_signals';

    protected $fillable = [
        'room',
        'from_user_id',
        'kind',
        'payload',
    ];

    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }
}
