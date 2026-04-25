<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserSubscription extends Model
{
    protected $fillable = [
        'user_id', 'plan_id', 'amount_paid',
        'payment_method', 'stripe_session_id',
        'status', 'starts_at', 'expires_at', 'cancelled_at',
    ];

    protected $casts = [
        'starts_at'    => 'datetime',
        'expires_at'   => 'datetime',
        'cancelled_at' => 'datetime',
        'amount_paid'  => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    // Auto-expire check
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    // Status badge color
    public function statusColor(): string
    {
        return match($this->status) {
            'active'    => 'success',
            'expired'   => 'secondary',
            'cancelled' => 'danger',
            default     => 'secondary',
        };
    }
}