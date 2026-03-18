<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    protected $fillable = [
        'type',
        'title',
        'message',
        'related_id',
        'related_type',
        'icon',
        'color',
        'url',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    // ── Unread count (static helper) ──
    public static function unreadCount(): int
    {
        return static::where('is_read', false)->count();
    }

    // ── Latest unread for bell dropdown ──
    public static function latestUnread(int $limit = 8)
    {
        return static::where('is_read', false)
            ->latest()
            ->limit($limit)
            ->get();
    }

    // ── Mark all as read ──
    public static function markAllRead(): void
    {
        static::where('is_read', false)->update(['is_read' => true]);
    }

    // ── Create helpers for each type ──

    public static function newUser(User $user): void
    {
        static::create([
            'type'         => 'new_user',
            'title'        => 'New User Registered',
            'message'      => $user->name . ' just joined Fobia.',
            'related_id'   => $user->id,
            'related_type' => 'User',
            'icon'         => 'person-add-outline',
            'color'        => 'primary',
            'url'          => '/admin/userdetail',
        ]);
    }

    public static function newReport(User $reported, string $reason): void
    {
        static::create([
            'type'         => 'new_report',
            'title'        => 'New Report Submitted',
            'message'      => $reported->name . ' was reported for: ' . $reason,
            'related_id'   => $reported->id,
            'related_type' => 'User',
            'icon'         => 'warning-outline',
            'color'        => 'danger',
            'url'          => '/admin/reports',
        ]);
    }

    public static function newSubscription(User $user, string $plan): void
    {
        static::create([
            'type'         => 'new_subscription',
            'title'        => 'New Subscription',
            'message'      => $user->name . ' subscribed to ' . ucfirst($plan) . ' plan.',
            'related_id'   => $user->id,
            'related_type' => 'User',
            'icon'         => 'diamond-outline',
            'color'        => 'success',
            'url'          => '/admin/userdetail',
        ]);
    }

    public static function newSupportTicket(User $user, string $subject): void
    {
        static::create([
            'type'         => 'support_ticket',
            'title'        => 'New Support Ticket',
            'message'      => $user->name . ' opened a ticket: ' . $subject,
            'related_id'   => $user->id,
            'related_type' => 'User',
            'icon'         => 'ticket-outline',
            'color'        => 'warning',
            'url'          => '/admin/support',
        ]);
    }

    public static function accountBlocked(User $user): void
    {
        static::create([
            'type'         => 'account_blocked',
            'title'        => 'Account Auto-Blocked',
            'message'      => $user->name . '\'s account was blocked due to multiple reports.',
            'related_id'   => $user->id,
            'related_type' => 'User',
            'icon'         => 'ban-outline',
            'color'        => 'danger',
            'url'          => '/admin/reports',
        ]);
    }

    public static function verificationRequest(User $user): void
    {
        static::create([
            'type'         => 'verification_request',
            'title'        => 'Verification Request',
            'message'      => $user->name . ' has requested profile verification.',
            'related_id'   => $user->id,
            'related_type' => 'User',
            'icon'         => 'shield-checkmark-outline',
            'color'        => 'info',
            'url'          => '/admin/verification',
        ]);
    }
}