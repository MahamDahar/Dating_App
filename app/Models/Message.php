<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'message_type',
        'media_path',
        'read_at',
        'hidden_for_sender_at',
        'hidden_for_receiver_at',
        'revoked_at',
        'starred_user_ids',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
            'hidden_for_sender_at' => 'datetime',
            'hidden_for_receiver_at' => 'datetime',
            'revoked_at' => 'datetime',
            'starred_user_ids' => 'array',
        ];
    }

    /** Plain-text preview for copy / starred list (not for revoked content). */
    public function previewPlain(): string
    {
        if ($this->isRevoked()) {
            return '';
        }
        if ($this->isImage()) {
            return '[Image]';
        }
        if ($this->isAudio()) {
            return '[Voice message]';
        }

        return \Illuminate\Support\Str::limit((string) $this->message, 500);
    }

    public function starredUserIdList(): array
    {
        $ids = $this->starred_user_ids;
        if (! is_array($ids)) {
            return [];
        }

        return array_values(array_unique(array_map('intval', array_filter($ids, fn ($v) => $v !== null && $v !== ''))));
    }

    public function isStarredBy(int $userId): bool
    {
        return in_array($userId, $this->starredUserIdList(), true);
    }

    /** Messages visible to this user (excludes “delete for me” only). */
    public function scopeVisibleToUser($query, int $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where(function ($q2) use ($userId) {
                $q2->where('sender_id', $userId)->whereNull('hidden_for_sender_at');
            })->orWhere(function ($q2) use ($userId) {
                $q2->where('receiver_id', $userId)->whereNull('hidden_for_receiver_at');
            });
        });
    }

    public function isRevoked(): bool
    {
        return $this->revoked_at !== null;
    }

    public function isText(): bool
    {
        return ($this->message_type ?? 'text') === 'text';
    }

    public function isImage(): bool
    {
        return ($this->message_type ?? '') === 'image';
    }

    public function isAudio(): bool
    {
        return ($this->message_type ?? '') === 'audio';
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
